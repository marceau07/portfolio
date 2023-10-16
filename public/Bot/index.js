const Discord = require("discord.js");
const fs = require("fs");
const config = require("./storage/config.json");
const bot = new Discord.Client();

bot.commands = new Discord.Collection();


fs.readdir("./commands/", (err, files) => {
    if(err) console.log(err);

    var jsFiles = files.filter(f => f.split(".").pop() === "js");
    if(jsFiles.length <= 0){
        console.log("Aucun fichier de commande !");
        return;
    }
    jsFiles.forEach((f, i) => {
        var fileGet = require("./commands/" + f);
        console.log("Fichier de commande " + f + " récupéré avec succès !");
        bot.commands.set(fileGet.help.name, fileGet);
    });
});

/*
* Connexion du bot
*/
bot.on("ready", async () => {
    console.log(" \nConnecté en tant que : " + bot.user.tag);
    bot.user.setActivity("!help | " + bot.user.tag, {type: "PLAYING"});
});

/*
* Exécution de la commande passée via '!'
*/
bot.on("message", message => {
    if(message.author.bot) return;
    if(message.channel.type === "dm") return;

    var prefix = config.prefix;
    var messageArray = message.content.split(" ");
    var command = messageArray[0];
    var args = messageArray.slice(1);
    var commands = bot.commands.get(command.slice(prefix.length));
    if(commands) commands.run(bot, message, args);
});

bot.login(config.token);

/*
* Message lorsqu'un utilisateur rejoint le serveur
*/
bot.on("guildMemberAdd", user =>{
    let joinEmbed = new Discord.RichEmbed()
        .setColor("#34e119")
        .setAuthor(user.user.username, user.user.displayAvatarURL)
        .setDescription("[+] " + user.user.username)
        .setFooter(user.guild.name + " | " + bot.user.tag);
    user.guild.channels
        .get("679534441512566805")
        .send(joinEmbed);
    user.addRole("679545145003212806");
});

/*
* Message lorsqu'un utilisateur quitte le serveur
*/
bot.on("guildMemberRemove", user =>{
    let leaveEmbed = new Discord.RichEmbed()
        .setColor("#CC0000")
        .setAuthor(user.user.username, user.user.displayAvatarURL)
        .setDescription("A bientôt sur **" + user.guild.name + "** :confetti_ball:")
        .setFooter(user.guild.name + " | " + bot.user.tag);
    user.guild.channels
        .get("679534441512566805")
        .send(leaveEmbed);
});