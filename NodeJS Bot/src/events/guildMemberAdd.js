const config = require('../../config.json');
const fetch = require('node-fetch');

module.exports = {
	name: 'guildMemberAdd',
	execute: async(member) => {
        fetch(`${config.WEBSITE_URL}api.php?action=findByDiscord&id=${member.user.id}&secret=${config.SECRET_KEY}`).then(res => res.text()).then(response => {
            if(response.toLowerCase().includes("no users")) return;
            member.roles.add(config.VERIFIED_ROLE);
        });
  }};
