const config = require('../../config.json');
const fetch = require('node-fetch');
const Discord = require('discord.js');
module.exports = {
	name: 'messageCreate',
	execute: async(message) => {
    if (message.author.bot) return;
    if (message.channel.type === 'DM') return;

    let embed = new Discord.EmbedBuilder();

    if(config.HOW_TO_LINK_EMBED.EMBED_TITLE) embed.setTitle(config.HOW_TO_LINK_EMBED.EMBED_TITLE);
    let newEmbedDescription = config.HOW_TO_LINK_EMBED.EMBED_DESCRIPTION.replace(/{websiteURL}/gi, config.WEBSITE_URL)

    if(config.HOW_TO_LINK_EMBED.EMBED_DESCRIPTION) embed.setDescription(newEmbedDescription);
    let newEmbedURL = config.HOW_TO_LINK_EMBED.TITLE_URL.replace(/{websiteURL}/gi, config.WEBSITE_URL)

    if(config.HOW_TO_LINK_EMBED.TITLE_URL) embed.setDescription(newEmbedURL);
    let newEmbedFooter = config.HOW_TO_LINK_EMBED.EMBED_FOOTER.replace(/{websiteURL}/gi, config.WEBSITE_URL)

    if(config.HOW_TO_LINK_EMBED.EMBED_FOOTER) embed.setFooter({ text: newEmbedFooter });
    if(config.HOW_TO_LINK_EMBED.EMBED_LARGE_IMAGE) embed.setImage(config.HOW_TO_LINK_EMBED.EMBED_LARGE_IMAGE);
    if(config.HOW_TO_LINK_EMBED.EMBED_SMALL_IMAGE) embed.setTitle(config.HOW_TO_LINK_EMBED.EMBED_SMALL_IMAGE);
    if(config.HOW_TO_LINK_EMBED.EMBED_COLOR) embed.setColor(config.HOW_TO_LINK_EMBED.EMBED_COLOR);


    if(message.content.toLowerCase().includes("auth") || message.content.toLowerCase().includes("verify") || message.content.toLowerCase().includes("link")) {
      if(config['LINKING INSTRUCTIONS'].DM_INSTRUCTIONS) {
        message.member.send({ embeds: [embed] }).catch(err => {
          console.log(err)
          message.reply("I can't DM a list of instructions on how to verify.\nPlease make sure your DMs are enabled");
        });
      }
      
      if(config['LINKING INSTRUCTIONS'].REPLY_IN_CHANNEL) {
        message.reply({ embeds: [embed] });
      }
    }
  }
};