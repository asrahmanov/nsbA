var CronJob = require('cron').CronJob;
const request = require('request');

var job = new CronJob('1/3 * * * * *', function () {

    request('http://93.157.172.122:2000/?message=' + getRandomIntInclusive(1,10), (err, res, body) => {
        console.log('body', body);
    })


}, null, true, 'America/Los_Angeles');



function getRandomIntInclusive(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min; //Максимум и минимум включаются
}





