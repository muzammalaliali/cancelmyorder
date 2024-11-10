const { Client, Collection, GatewayIntentBits, Partials } = require("discord.js");
const client = new Client({intents: [GatewayIntentBits.Guilds, GatewayIntentBits.GuildMembers, GatewayIntentBits.GuildEmojisAndStickers, GatewayIntentBits.GuildIntegrations, GatewayIntentBits.GuildWebhooks, GatewayIntentBits.GuildInvites, GatewayIntentBits.GuildVoiceStates, GatewayIntentBits.GuildPresences, GatewayIntentBits.GuildMessages, GatewayIntentBits.GuildMessageReactions, GatewayIntentBits.GuildMessageTyping, GatewayIntentBits.DirectMessages, GatewayIntentBits.DirectMessageReactions, GatewayIntentBits.DirectMessageTyping, GatewayIntentBits.MessageContent], shards: "auto", partials: [Partials.Message, Partials.Channel, Partials.GuildMember, Partials.Reaction, Partials.GuildScheduledEvent, Partials.User, Partials.ThreadMember]});
const config = require("./config.json");
const { readdirSync } = require("fs")
const moment = require("moment");
const { REST } = require('@discordjs/rest');
const { Routes } = require('discord-api-types/v10');
if(!config.WEBSITE_URL.endsWith("/")) config.WEBSITE_URL = config.WEBSITE_URL + "/";

client.commands = new Collection()

const rest = new REST({ version: '10' }).setToken(config.BOT_TOKEN);

const commands = [];
readdirSync('./src/commands').forEach(async file => {
  const command = require(`./src/commands/${file}`);
  commands.push(command.data.toJSON());
  client.commands.set(command.data.name, command);
})

client.on("ready", async () => {
        try {
          console.log('Starting to load slash commands');

            await rest.put(
                Routes.applicationGuildCommands(client.user.id, config.DISCORD_GUILD_ID),
                { body: commands },
            );

            console.log('Successfully reloaded application slash commands.');

        } catch (error) {
            console.error(error);
        }
    console.log(`Logged in as: ${client.user.tag}`);
})

readdirSync('./src/events').forEach(async file => {
	const event = require(`./src/events/${file}`);
	if (event.once) {
		client.once(event.name, (...args) => event.execute(...args));
	} else {
		client.on(event.name, (...args) => event.execute(...args));
	}
})

process.on("unhandledRejection", err => { 
   console.log(err)
 }) 
process.on("uncaughtException", err => { 
   console.log(err)
 })  
process.on("uncaughtExceptionMonitor", err => { 
   console.log(err)
 })


client.login(config.BOT_TOKEN)
