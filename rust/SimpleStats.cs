using System;
using System.Collections.Generic;
using System.Linq;
using Newtonsoft.Json;
using Oxide.Core.Libraries;

namespace Oxide.Plugins
{
    [Info("SimpleStats", "Astinox", "1.0.4")]
    internal class SimpleStats : RustPlugin
    {
        #region Static

        private Configuration _config;
		
		private Dictionary<ulong, WoundedPlayer> woundedPlayers = new Dictionary<ulong, WoundedPlayer>();
		
		private List<BodyAPI> RequestList = new List<BodyAPI>();

        #endregion

        #region Config

        private class Configuration
        {
			[JsonProperty(PropertyName = "ApiURL", ObjectCreationHandling = ObjectCreationHandling.Replace)]
            public string ApiURL = "";
			
			[JsonProperty(PropertyName = "SecretKey", ObjectCreationHandling = ObjectCreationHandling.Replace)]
            public string SecretKey = "";
			
			[JsonProperty(PropertyName = "ServerID", ObjectCreationHandling = ObjectCreationHandling.Replace)]
            public string ServerID = "1";
			
			[JsonProperty(PropertyName = "Count suicide kills", ObjectCreationHandling = ObjectCreationHandling.Replace)]
            public bool CountSuicideKills = false;
			
			[JsonProperty(PropertyName = "Count environment kills", ObjectCreationHandling = ObjectCreationHandling.Replace)]
            public bool CountEnvironmentKills = false;

			[JsonProperty(PropertyName = "UntrackedPlayerIds", ObjectCreationHandling = ObjectCreationHandling.Replace)]
            public List<ulong> IgnorePlayers = new List<ulong>(){};
        }

        protected override void LoadConfig()
        {
            base.LoadConfig();
            try
            {
                _config = Config.ReadObject<Configuration>();
                if (_config == null) throw new Exception();
                SaveConfig();
            }
            catch
            {
                PrintError("Your configuration file contains an error. Using default configuration values.");
                LoadDefaultConfig();
            }
        }

        protected override void SaveConfig()
        {
            Config.WriteObject(_config);
        }

        protected override void LoadDefaultConfig()
        {
            _config = new Configuration();
        }

        #endregion
		
		#region Hooks
		
		private void Init()
		{
			timer.Every(60f, () =>
			{
				PerformApiRequest();
			});
		}
		
		private void OnServerShutdown()
		{
			PerformApiRequest();
		}
		
		
		private object OnPlayerDeath(BasePlayer player, HitInfo info)
		{
			if(player == null || _config.IgnorePlayers.Contains(player.userID)) return null;
			
			// Ignore NPCs
			if(!player.userID.IsSteamId()) return null;
			
		
			
			// If a player is in the wounded list we can make sure that the player was killed by another player
			// in that case we will add the info before the other checks, as the "original kill" (wounding) was
			// performed by that player. Even if the final kill is done by another guy.
			if(woundedPlayers.ContainsKey(player.userID)) {
				
				RequestList.Add(new BodyAPI()
				{
					steamid = player.UserIDString,
					_event = "death",
					amount = "1"
				});
				
				if(_config.IgnorePlayers.Contains(woundedPlayers[player.userID].attackerId)) {
					woundedPlayers.Remove(player.userID);
					return null;
				}
				
				RequestList.Add(new BodyAPI()
				{
					steamid = woundedPlayers[player.userID].attackerId.ToString(),
					_event = "kill",
					amount = "1"
				});
				
				woundedPlayers.Remove(player.userID);
				return null;
			}
	
			if(info == null) {
				
				// Unknown kill (E.g. fall, counting as environmental kill)
				if(!_config.CountEnvironmentKills) return null;

				RequestList.Add(new BodyAPI()
				{
					steamid = player.UserIDString,
					_event = "death",
					amount = "1"
				});
				
			} else {
				
				// Environmental kill (Cold, Drowning, NPCs, Animals)
				if(info.Initiator == null || info.InitiatorPlayer == null || !info.InitiatorPlayer.userID.IsSteamId()) {
					
					if(!_config.CountEnvironmentKills) return null;
					
					RequestList.Add(new BodyAPI()
					{
						steamid = player.UserIDString,
						_event = "death",
						amount = "1"
					});
					
					
				} else {
					
					if(info.InitiatorPlayer != null && info.InitiatorPlayer.userID != player.userID) {
						
						// Normal kill, without wounded
						RequestList.Add(new BodyAPI()
						{
							steamid = player.UserIDString,
							_event = "death",
							amount = "1"
						});
						
						if(_config.IgnorePlayers.Contains(info.InitiatorPlayer.userID)) return null;
						
						RequestList.Add(new BodyAPI()
						{
							steamid = info.InitiatorPlayer.UserIDString,
							_event = "kill",
							amount = "1"
						});

						
					} else {
						// It was suicide
						if(!_config.CountSuicideKills) return null;
						
						RequestList.Add(new BodyAPI()
						{
							steamid = player.UserIDString,
							_event = "death",
							amount = "1"
						});

					}
					
			
				}
			
			}
			
			woundedPlayers.Remove(player.userID);
			return null;
		}
		
		private object OnPlayerWound(BasePlayer player, HitInfo info)
		{
			if (player == null || info == null) return null;
			if(info.InitiatorPlayer == null) return null;
			if(!info.InitiatorPlayer.userID.IsSteamId()) return null;
			
			woundedPlayers[player.userID] = new WoundedPlayer { attackerId = info.InitiatorPlayer.userID };
			return null;
		}
		
		private void OnPlayerRecovered(BasePlayer player)
        {
            if (woundedPlayers.ContainsKey(player.userID))
				woundedPlayers.Remove(player.userID);
        }
        
        private void OnPlayerDisconnected(BasePlayer player, string reason)
        {
			if (_config.IgnorePlayers.Contains(player.userID)) return;
			
			RequestList.Add(new BodyAPI()
			{
				steamid = player.UserIDString,
				_event = "playtime",
				amount = $"{player.secondsConnected}"
			});
			
			if (woundedPlayers.ContainsKey(player.userID))
				woundedPlayers.Remove(player.userID);
        }
		
		#endregion

        #region Function

        private void PerformApiRequest()
        {
			webrequest.Enqueue($"{_config.ApiURL}?secret={_config.SecretKey}&server={_config.ServerID}",  JsonConvert.SerializeObject(RequestList), (i, s) =>
			{
				// We clear the list of requests anytime, even if the API request has failed. This is done to prevent an overload of the server
				// when storing hundreds of entries on it. This way it will get cleared no matter what.
				RequestList.Clear();
				
				if (i != 200)
				{
					PrintError($"API request failed. If this happens multiple times, please contact the developer! Error Code: {i} {s}");
					return;
				}
				
			}, this,RequestMethod.POST);
        }
		
        private class BodyAPI
        {
            public string steamid;
            public string _event;
            public string amount;
        }
		
		private class WoundedPlayer
        {
            public ulong attackerId;
        }
		
        #endregion
    }
}