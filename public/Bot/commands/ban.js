const Discord = require("discord.js");

module.exports.run = async (bot, message, args) => {
    message.delete();
    
    if(!message.member.hasPermission("BAN_MEMBERS")) return message.channel.send(":x: Vous n'avez pas les permissions pour exécuter cette commande.").then(m => m.delete(5000));
    let user = message.mentions.members.first();
    if(!user) return message.channel.send(":x: | Veuillez mentionner un utilisateur valide.").then(m => m.delete(5000));
    if(user.highestRole.calculatedPosition >= message.member.highestRole.calculatedPosition && message.author.id != message.guild.ownerID) return message.channel.send(":x: | Vous ne pouvez pas exclure cet utilisateur. (Permission insufisante).").then(m => m.delete(5000));
    let reason = args.slice(1).join(" ");
    if(!reason) return message.channel.send(":x: | Veuillez indiquer une raison.").then(m => m.delete(5000));
    if(!user.kickable) return message.channel.send(":x: | Cet utilisateur ne peut pas être banni.").then(m => m.delete(5000));
    let banEmbed = new Discord.RichEmbed()
        .setColor("#FCD404")
        .setAuthor(bot.user.tag)
        .setDescription("\nVous avez été banni du serveur " + user.guild.name + "\n**Banni par:** " + message.author.username + "\n**Raison:** " + reason)
        .setFooter(user.guild.name + " | " + bot.user.tag);
    user.send(banEmbed);
    function banUser(){
        user.ban(reason);
        message.channel.send(":white_check_mark: | " + user.user.username + " a été banni du serveur !").then(m => m.delete(5000));
    }
    setTimeout(banUser(), 1000);
}

module.exports.help = {
    name: "ban"
}