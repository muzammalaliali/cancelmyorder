const fetch = require('node-fetch');
const config = require('../../config.json');

const updateNitro = (user_id, status) => {
    return new Promise((resolve, reject) => {
        let SiteURL = config.WEBSITE_URL;
        if (!SiteURL.endsWith("/")) SiteURL += "/";

        fetch(`${SiteURL}api.php?action=updateNitro&status=${status}&secret=${config.SECRET_KEY}&id=${user_id}`)
            .then(response => {
                if (response.ok) {
                    resolve();
                } else {
                    reject(`Failed to update Nitro status for user ${user_id}`);
                }
            })
            .catch(err => {
                reject(`Error updating Nitro status for user ${user_id}: ${err}`);
            });
    });
};

module.exports = updateNitro;