const config = require('../../config.json');
const fetch = require('node-fetch');

module.exports = {
	name: 'guildMemberRemove',
	execute: async(member) => {
        fetch(`${config.WEBSITE_URL}api.php?action=remove&id=${member.user.id}&secret=${config.SECRET_KEY}`).then(res => res.text()).then(response => {
            console.log(response);
        }).catch(err => console.log(err));
  }};
