const { EmbedBuilder, PermissionsBitField } = require("discord.js");
const { SlashCommandBuilder } = require("@discordjs/builders");
const fetch = require('node-fetch');
const config = require('../../config.json');
const Discord = require('discord.js');

module.exports = {
  data: new SlashCommandBuilder()
    .setName("search-link")
    .setDescription("Check to see if a user is linked")
    .addStringOption(Option => Option.setName('steamid').setDescription("Steam 64 ID of user you want to check").setRequired(false))
    .addUserOption(Option => Option.setName('user').setDescription("Discord user you want to check links for").setRequired(false)),
    run: async (client, interaction) => {
      const embed = new Discord.EmbedBuilder();
      if(interaction.options._hoistedOptions.length == 0) {
        fetch(`${config.WEBSITE_URL}api.php?action=count&secret=${config.SECRET_KEY}`).then(res => res.json()).then(response => {

            interaction.reply({ embeds: [embed.setDescription(`There are currently ${response.Total} users verified!`)] })

        }).catch(err => console.log(err));
      } else {
        let user = interaction.options._hoistedOptions[0].value;
        if(interaction.options._hoistedOptions[0].name == "user") {
          fetch(`${config.WEBSITE_URL}api.php?action=findByDiscord&id=${user}&secret=${config.SECRET_KEY}`).then(res => res.text()).then(response => {

            if(response.toLowerCase().includes("no users")) return interaction.reply("No linked user found");
            try {
              let json = JSON.parse(response);
              let resultEmbed = new EmbedBuilder()
                  .setTitle("Link Results | Steam Found")
                  .setColor('#4286f4')
                  .setThumbnail(config.LOGO_URL)
                  .setDescription(`Located the Steam ID ${json} for the Discord ID ${user}.`)
                  .addFields({ name: "Steam ID", value: json, inline: true })
                  .addFields({ name: "Discord ID", value: user, inline: true })
                  .addFields({ name: "Links", value: `[[BattleMetrics]](https://www.battlemetrics.com/players?filter%5Bsearch%5D=${json}&filter%5BplayerFlags%5D=&sort=score)\n[[Steam Profile]](https://steamcommunity.com/profile/${json})`, inline: false})
                  .setFooter({ text: config.SERVER_NAME, iconURL: config.LOGO_URL });
              interaction.reply({ embeds: [resultEmbed] });
            } catch {
              let resultEmbed = new EmbedBuilder()
                .setTitle("Link Results | Steam Found")
                .setColor('#4286f4')
                .setThumbnail(config.LOGO_URL)
                .setDescription(`Located the Steam ID ${response} for the Discord ID ${user}.`)
                .addFields({ name: "Steam ID", value: response, inline: true })
                .addFields({ name: "Discord ID", value: user, inline: true })
                .addFields({ name: "Links", value: `[[BattleMetrics]](https://www.battlemetrics.com/players?filter%5Bsearch%5D=${response}&filter%5BplayerFlags%5D=&sort=score)\n[[Steam Profile]](https://steamcommunity.com/profile/${response})`, inline: false})
                .setFooter({ text: config.SERVER_NAME, iconURL: config.LOGO_URL });
            interaction.reply({ embeds: [resultEmbed] });
            }
          });
        } else {
          fetch(`${config.WEBSITE_URL}api.php?action=findBySteam&id=${user}&secret=${config.SECRET_KEY}`).then(res => res.text()).then(response => {
            if(response.toLowerCase().includes("no users")) return interaction.reply("No linked user found");
            try {
              let json = JSON.parse(response);
                let resultEmbed = new EmbedBuilder()
                .setTitle("Link Results | Discord Found")
                .setColor('#4286f4')
                .setThumbnail(config.LOGO_URL)
                .setDescription(`Located the Discord ID ${json} for the Steam ID ${user}.`)
                .addFields({ name: "Steam ID", value: user, inline: true })
                .addFields({ name: "Discord ID", value: json, inline: true })
                .addFields({ name: "Links", value: `[[BattleMetrics]](https://www.battlemetrics.com/players?filter%5Bsearch%5D=${user}&filter%5BplayerFlags%5D=&sort=score)\n[[Steam Profile]](https://steamcommunity.com/profile/${user})`, inline: false})
                .setFooter({ text: config.SERVER_NAME, iconURL: config.LOGO_URL });
            interaction.reply({ embeds: [resultEmbed] });
            } catch {
              let resultEmbed = new EmbedBuilder()
                    .setTitle("Link Results | Discord Found")
                    .setColor('#4286f4')
                    .setThumbnail(config.LOGO_URL)
                    .setDescription(`Located the Discord ID ${response} for the Steam ID ${user}.`)
                    .addFields({ name: "Steam ID", value: user, inline: true })
                    .addFields({ name: "Discord ID", value: response, inline: true })
                    .addFields({ name: "Links", value: `[[BattleMetrics]](https://www.battlemetrics.com/players?filter%5Bsearch%5D=${user}&filter%5BplayerFlags%5D=&sort=score)\n[[Steam Profile]](https://steamcommunity.com/profile/${user})`, inline: false})
                    .setFooter({ text: config.SERVER_NAME, iconURL: config.LOGO_URL });
                interaction.reply({ embeds: [resultEmbed] });
            }
          });
        }
      }
    }
 };