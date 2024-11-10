const fetch = require('node-fetch');
const config = require('../../config.json');

const getTotalLinked = () => {
    
    return new Promise((resolve, reject) => {

        let SiteURL = config.WEBSITE_URL;
        if(!config.WEBSITE_URL.endsWith("/")) SiteURL = config.WEBSITE_URL + "/";
        fetch(`${SiteURL}api.php?action=count&secret=${config.SECRET_KEY}`).then(res => res.text()).then(response => {
            try {
                response = JSON.parse(response);
                resolve(response.Total);
            } catch (err) { 
                reject(err);
            }
        });

    });
 
}

module.exports = getTotalLinked;
