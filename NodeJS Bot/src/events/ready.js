const { ActivityType, PermissionsBitField, Client, Intents } = require("discord.js");
const config = require('../../config.json');
const fetch = require('node-fetch');
const getTotalLinked = require('../functions/getTotalLinked.js');
const updateNitro = require('../functions/updateNitro.js');

module.exports = {
  name: 'ready',
  once: true,
  async execute(client) {
    let activities = config.BOT_STATUS, i = 0;

    const theGuild = client.guilds.cache.get(config.DISCORD_GUILD_ID);
    if (!theGuild) return;

    let botMember = theGuild.members.cache.get(client.user.id);
    if (!botMember) return;

    if (config.SYNC_NAMES) {
      setInterval(nameSyncing, 300000);
      nameSyncing();
    }

    setInterval(nitroSyncing, 90000);
    nitroSyncing();

    setInterval(async () => {
      // CHANGE BOT ACTIVITY
      let currentActivity = activities[i++ % activities.length];
      if (currentActivity.includes('{linked}')) {
        try {
          const response = await getTotalLinked();
          currentActivity = currentActivity.replace(/{linked}/gi, response);
        } catch (err) {
          currentActivity = currentActivity.replace(/{linked}/gi, "error");
        }
      }
      currentActivity = currentActivity.replace(/{site}/gi, config.WEBSITE_URL);
      client.user.setActivity({ name: `${currentActivity}`, type: ActivityType.Watching });
    }, 30000);

    // CHECK IF USER IS STILL NITRO
    setInterval(async () => {
      try {
        const response = await fetch(`${config.WEBSITE_URL}api.php?action=listNitro&secret=${config.SECRET_KEY}`);
        const data = await response.json();

        console.log("Running nitro checker...");

        if (data.Result === 0) {
          console.log("No nitro users found");
          return;
        }

        const nitroUsers = data.Result;

        for (const user of nitroUsers) {
          setTimeout(async () => {
            const theMember = await theGuild.members.cache.get(user.discord_id);

            if (!theMember) return;

            if (!theMember.premiumSince) {
              console.log(`(${theMember.user.tag} / ${theMember.user.id}) has nitro rank, but is no longer nitro`);
              await updateNitro(theMember.user.id, 0);
            } else {
              console.log(`${theMember.user.tag} is still nitro`);
            }
          }, 1000 * nitroUsers.indexOf(user));
        }
      } catch (err) {
        console.error("Error in nitroSyncing:", err);
      }
    }, 600000);

    async function nitroSyncing() {
      try {
        await theGuild.members.fetch();
    
        const boosters = theGuild.members.cache.filter(member => member.premiumSinceTimestamp);
        if (boosters.size === 0) {
          console.log("No Nitro boosters found");
          return;
        }
    
        const boosterIds = Array.from(boosters.keys());
        const sendBoosters = JSON.stringify({ discord_ids: boosterIds });
    
        const body = JSON.stringify({ discord_ids: boosterIds });
        const response = await fetch(`${config.WEBSITE_URL}api.php?action=updateNitroBulk&secret=${config.SECRET_KEY}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: body
        });
    
        const responseData = await response.json();
    
        for (const userId of boosterIds) {
          await updateNitro(userId, 1);
          console.log(`Nitro status updated successfully for user ${userId}`);
          await new Promise(resolve => setTimeout(resolve, 1000));
        }
      } catch (error) {
        console.error("Error in nitroSyncing:", error);
      }
    }

    async function nameSyncing() {
      console.log("Now running name syncing functions...");
      let botMember = theGuild.me || theGuild.members.me;

      if (!botMember) return console.log("Bot member not found in the guild.");

      if (!botMember.permissions.has(PermissionsBitField.Flags.ManageNicknames, false)) {
        return console.log("I cannot manage nicknames, I do not have permission.");
      }

      const theMembers = await theGuild.members.fetch();

      let nicknamesRequest = await fetch(`${config.WEBSITE_URL}api.php?action=listAllNicknames&secret=${config.SECRET_KEY}`);
      let allNicknames = await nicknamesRequest.json();

      const discordIds = theMembers.filter(member => member.roles.cache.has(config.VERIFIED_ROLE) && member.nickname != allNicknames[member.user.id] && member.user.username != allNicknames[member.user.id]).map(member => member.user.id);
      if (discordIds.length === 0) return;

      console.log(`There are ${discordIds.length} IDs to update.`);

      for (const user of discordIds) {
        const member = await theGuild.members.cache.get(user);
        if (!member) {
          console.log(`Could not find Discord Member for ${user}`);
          continue;
        }

        if (allNicknames[member.id] == "False" || allNicknames[member.id] == null || allNicknames[member.id] == undefined) continue;
        let storedNickname = allNicknames[member.id];

        if (member.id == theGuild.ownerId) continue;
        if (member.nickname == storedNickname) continue;
        if (member.nickname == null && member.user.username == storedNickname) continue;
        if (member.nickname == undefined && member.user.username == storedNickname) continue;

        await member.setNickname(storedNickname).catch(err => { console.log(`Error setting nickname\n${err}`); });
        await new Promise(resolve => setTimeout(resolve, 2000));
      }
    }
  }
};