var CronJob = require('cron').CronJob;
const nodemailer = require('nodemailer');
const request = require('request');


var mysql = require('mysql');
var connection = mysql.createConnection({
    connectionLimit: 100,
    connectTimeout: 86000000,
    host: 'localhost',
    user: 'root',
    password: 'ghjuhfvvf',
    database: '_nbs',
    port: 3306
});
connection.connect();


var job = new CronJob('50 * * * * *', function () {
    check();
    TicketCheck();
}, null, true, 'Europe/Moscow');

job.start();


var errorRemainder = new CronJob('* * * * * *', function () {
    chekErrorRemainder();
}, null, true, 'Europe/Moscow');

errorRemainder.start();

// Отправка просроченных договоро в для юриста
let checkContract = new CronJob('0 15 11 * * *', function () {
    checkContracts();
}, null, true, 'Europe/Moscow');

checkContract.start();




// Рассылка на почтук информации по не предоставленной информации по образцам
let clinicalCaseMonday = new CronJob('0 30 10 * * 1', function () {
    checkClinicalCase();
}, null, true, 'Europe/Moscow');

let clinicalCaseThursday = new CronJob('0 0 10 * * 4', function () {
    checkClinicalCase();
}, null, true, 'Europe/Moscow');

clinicalCaseMonday.start();
clinicalCaseThursday.start();


let checkClinicalCase = () => {

    request.post({
        url: 'https://crm.i-bios.com/ClinicalCase/sendMailLost'
    }, function (error, response, body) {
        console.log('Данные отправлены!');

    });


};


// let cronSendReportForClient = new CronJob('0 0 17 * * 5', function () {
//     console.log('send');
//     sendReportForClient();
// }, null, true, 'Europe/Moscow');

// cronSendReportForClient.start();

// Отправка данных клиентам по состояниии их заявок
// let sendReportForClient = () => {
//
//     request.post({
//         url: 'https://crm.i-bios.com/ReportOrders/GenerateReportClient/?script_id=86&action=send'
//     }, function (error, response, body) {
//         console.log('Письма отправлены отправлены!');
//     });
//
//
// };


let TicketCheck = () => {

    request.post({
        url: 'https://crm.i-bios.com/api/TicketCheck'
    }, function (error, response, body) {
        console.log('Данные отправлены!');

    });


};



let checkContracts = () => {
    connection.query(`SELECT DATEDIFF(NOW(),fr_scripts.contract_off ) as total, contract_off, company_name, script, script_id FROM fr_scripts
                      HAVING total > -30`, function (error, rows) {
        if (!error) {
            console.log('что то нашел');
            let dlina = rows.length;
            if (dlina != 0) {
                for (let i = 0; i < dlina; i++) {
                    console.log('что то нашел ' + i);
                    let date_contract = rows[i].contract_off;

                    let fullYear = date_contract.getFullYear();
                    let month = date_contract.getMonth() + 1;
                    let day = date_contract.getDate();

                    let textMail = `У компании <a href="https://crm.i-bios.com/company/info/?companyId=${rows[i].script_id}" >${rows[i].script} ${rows[i].company_name}</a> - дата окончания договора ${day}.${month}.${fullYear}`;
                    //let email = 'olga.karaeva@nbioservice.com';
                    let email = 'asrahmanov@gmail.com';
                    let subject = 'Необходима пролонгация договора';

                    main(textMail, email, subject, 1).then(() => {
                        console.log(`Send mail to email ${email}`);
                    })


                }
            }


        } else {
            console.log('Ошибка запроса');
        }

    });

};

let chekErrorRemainder = () => {
    connection.query(`update nbs_new_tickets set done = 0 where isnull(done)`, function (error, rows) {
        if (!error) {
            // console.log('remainder ok');

        } else {
            console.log('Ошибка запроса');
        }

    });

};


let check = () => {
    let today = new Date();
    let year = today.getFullYear();
    let month = today.getMonth() + 1;
    if (month < 10) {
        month = `0${month}`;
    }
    let day = today.getDate();
    if (day < 10) {
        day = `0${day}`;
    }
    let hours = today.getHours();
    let minutes = today.getMinutes();
    let date = `${year}-${month}-${day} ${hours}:${minutes}:00`;

    connection.query(`SELECT * FROM nbs_mail WHERE send='NO' AND send_time < '${date}' `, function (error, rows) {
        if (!error) {
            console.log('Working ... ')
            let dlina = rows.length;
            if (dlina != 0) {
                for (let i = 0; i < dlina; i++) {
                    console.log('loop -  ok');
                    let textMail = rows[i].text_mail;
                    let email = rows[i].email;
                    let subject = rows[i].subject;
                    let replyTo = rows[i].reply_to;
                    let action = rows[i].action;
                    let proj_id = rows[i].proj_id;

                    if (email !== '' && email !== null && email !== undefined) {
                        console.log('action',action)
                        if(action == 'send') {

                            connection.query(`SELECT * FROM nbs_files  WHERE info='send' AND proj_id=${proj_id} AND deleted != '1'`, function (error, rows_files) {
                                if (!error) {
                                    console.log('Working ... ')
                                    let dlina = rows_files.length;
                                    if (dlina != 0) {
                                        let attachments = [];

                                        for (let i = 0; i < dlina; i++) {
                                            attachments.push({   // utf-8 string as an attachment
                                                filename: rows_files[i].name,
                                                path: encodeURI(`https://crm.i-bios.com/${rows_files[i].alias}/${rows_files[i].name}`),
                                            })
                                            console.log('attachments',attachments)
                                        }

                                        setTimeout(() => {
                                            main(textMail, email, subject, rows[i].id, replyTo, action,attachments).then(id => {
                                                console.log(`Send mail to email ${email} - id: ${rows[i].id}`);
                                                connection.query(`UPDATE nbs_mail SET send='YES'  WHERE id=${rows[i].id} LIMIT 1 `)
                                            })
                                        },1000)



                                    }
                                } else {

                                    //console.log('Запрос не прошел');
                                }
                            });


                        } else {
                            main(textMail, email, subject, rows[i].id, replyTo, action).then(id => {
                                console.log(`Send mail to email ${email} - id: ${id}`);
                                connection.query(`UPDATE nbs_mail SET send='YES'  WHERE id=${rows[i].id} LIMIT 1 `)
                            })
                        }

                    } else {
                        connection.query(`UPDATE nbs_mail SET send='ERROR'  WHERE id=${rows[i].id} LIMIT 1 `)
                        console.log(`error`)
                    }


                }
            }
        } else {

            //console.log('Запрос не прошел');
        }
    });
}


async function main(textMail, email, subject, id, replyTo, action, attachments = []) {
    console.log('id',id)
    let transporter = nodemailer.createTransport({
        host: "smtp.mail.ru",
        port: 465,
        secure: true, // true for 465, false for other ports
        auth: {
            user: "i-bioscrm@i-bios.com", // generated ethereal user
            pass: "cB1Vdy9iiETRctdhFec1" // generated ethereal password
            // pass: "t*poYagKI1I2" // generated ethereal password
        }
    });





    // send mail with defined transport object
    let info = await transporter.sendMail({
        from: 'i-bioscrm@i-bios.com', // sender address
        to: email, // list of receivers
        replyTo: replyTo, // list of receivers
        subject: subject, // Subject line
        //text: textMail, // plain text body
        html: textMail, // html body
        attachments: attachments
    });

    сonsole.log('info',info);

    return id

}

