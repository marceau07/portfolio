/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var _archived = document.getElementById("archivedProjects");
var _wip = document.getElementById("wipProjects");
var _finished = document.getElementById("finishedProjects");
var _total = document.getElementById("totalProjects");

var archived = document.getElementsByClassName("archived").length;
var wip = document.getElementsByClassName("wip").length;
var finished = document.getElementsByClassName("finished").length;
var total = wip + finished;

_archived.textContent = archived;
_wip.textContent = wip;
_finished.textContent = finished;
_total.textContent = total;