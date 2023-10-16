const Discord = require("discord.js");

module.exports.run = async (bot, message, args) => {
    message.delete();
    
    if(!message.member.hasPermission("KICK_MEMBERS")) return message.channel.send(":x: Vous n'avez pas les permissions pour exécuter cette commande.").then(m => m.delete(5000));
    let user = message.mentions.members.first();
    if(!user) return message.channel.send(":x: | Veuillez mentionner un utilisateur valide.").then(m => m.delete(5000));
    if(user.highestRole.calculatedPosition >= message.member.highestRole.calculatedPosition && message.author.id != message.guild.ownerID) return message.channel.send(":x: | Vous ne pouvez pas exclure cet utilisateur. (Permission insufisante).").then(m => m.delete(5000));
    let reason = args.slice(1).join(" ");
    if(!reason) return message.channel.send(":x: | Veuillez indiquer une raison.").then(m => m.delete(5000));
    if(!user.kickable) return message.channel.send(":x: | Cet utilisateur ne peut pas être exclu.").then(m => m.delete(5000));
    user.send("Vous avez été exclu du serveur par " + message.author.username + " pour la raison " + reason + " !");
    function kickUser(){
        user.kick(reason);
        message.channel.send(":white_check_mark: | " + user.user.username + " a été exclu du serveur !").then(m => m.delete(5000));
    }
    setTimeout(kickUser(), 1000);
}

module.exports.help = {
    name: "kick"
}