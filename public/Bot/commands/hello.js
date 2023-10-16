const Discord = require("discord.js");
const config = require("../storage/config.json");

module.exports.run = async (bot, message, args) => {
    var prefix = config.prefix;

    if(!message.guild) return;
    if(message.content === prefix + "hello"){   
        message.channel.send("Bonjour " + message.author + " !");
    }
}

module.exports.help = {
    name: "hello"
}