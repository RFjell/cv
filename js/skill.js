'use strict';

// Place that displays status message
var msg = document.getElementById('upload-message');
// Div that displays the loading animation
var loader = document.getElementById('skill-loader');

function addSkill(sn, si) {
	var skillName;
	var skillId;
	if(arguments.length < 2) {
		// Select with name of skill that will be added
		var skill = document.getElementById('skill');
		skillName = skill.options[skill.selectedIndex].text;
		skillId = skill.options[skill.selectedIndex].value;
		if(!skillId) // if skill level has not been chosen
			return;
	} else {
		skillName = sn;
		skillId = si;
	}
	// Div that contains all skills
	var skillDiv = document.getElementById('skills');

	// The div that will contain the label and di
	var div = document.createElement("div");
	// The div that will contain the skill level select and delete button
	var di = document.createElement("div");
	// Label that will contain name of the skill
	var label = document.createElement("label");
	// The skill level select
	var select = document.createElement("select");


	// Prepare the new select
	var select = document.createElement("div");
	select.id = "skill"+skillId;
	select.setAttribute("data-name","skill"+skillId);
	select.className ='rating ';

	for( let i = 5; i >= 1; i-- ) {
		let option = document.createElement("span");
		option.setAttribute("data-value", i);
		option.setAttribute("data-skillId", skillId);
		option.textContent = "☆";
		option.addEventListener('click',function() {
			updateSkill(this);
		});
		select.appendChild(option);
	}

	// Text that will appear in label
	var content = document.createTextNode(skillName);
	label.appendChild(content);

	//Create the delete button
	var deleteBtn = document.createElement("span");
	deleteBtn.setAttribute("class","remove-skill-button");
	deleteBtn.addEventListener('click',function() {
			deleteSkill( skillId, skillName)
	});
	deleteBtn.textContent = "×";

	// Add all the new elements
	div.appendChild(label);
	di.appendChild(select);
	di.appendChild(document.createTextNode("	"));
	di.appendChild(deleteBtn);
	di.appendChild(document.createElement("br"));
	div.appendChild(di);
	select.className +='fade';
	div.className ='fade';
	skillDiv.insertBefore(div, skillDiv.firstChild);
	//skillDiv.appendChild(div);

	// Remove the chosen skill from the list of all skills
	if(arguments.length < 2) {
		skill.remove(skill.selectedIndex);
	}

}

function updateSkill(skill) {
	var skillId = skill.getAttribute('data-skillId');
	var skillLevel = skill.getAttribute('data-value');
	//TODO: don't update stars if failure
	postSkill( skillId, skillLevel );

	var parentElement = skill.parentElement;
	removeChildren( parentElement );
	for( let i = 5; i >= 1; i-- ) {
		let option = document.createElement("span");
		option.setAttribute("data-value", i);
		option.setAttribute("data-skillId", skillId);
		if( skillLevel < i )
			option.textContent = "☆";
		else
			option.textContent = "★";
		option.addEventListener('click',function() {
			updateSkill(this);
		});
		parentElement.appendChild(option);
	}
}

function deleteSkill( skillId, skillName ) {

	// Data to be sent to server
	var formData = new FormData();
	formData.append('skill-id', skillId);

	msg.textContent = 'Deleting skill...';
	loader.className = 'loader';

	xhr('user/remove-skill.php', formData, function (srvRes) {
		// Delete skill from profile's list of skills
		var parentElement = document.getElementById('skill'+skillId).parentElement.parentElement;
		removeChildren(parentElement);
		parentElement.parentElement.removeChild(parentElement);

		// Update status message and stop loading animation
		loader.className = '';
		msg.textContent = 'Skill deleted!';
		setTimeout( function(){msg.textContent = '';}, 5000 );

		//Re-add deleted skill to list of all skills
		var option = document.createElement('option');
		option.setAttribute("value", skillId);
		option.textContent = skillName;
		var s = document.getElementById('skill');
		if( s.length === 1)
			s.appendChild(option);
		for( let i = 1; i < s.length; i++ ){
			if( s.options[i].text.toLowerCase() > skillName.toLowerCase() ) {
				s.insertBefore(option, s.options[i]);
				break;
			}
			if( i == s.length-1 )
				s.appendChild(option);
		}
		search();
	});
}

function postSkill(skillId, skillLevel) {
	// Data that will be sent
	var formData = new FormData();
	formData.append('skill', skillId);
	formData.append('skill-level', skillLevel);

	// Display message and loading animation
	msg.textContent = 'Updating skill..';
	loader.className = 'loader';

	xhr('user/add-or-update-skill.php', formData, function (srvRes) {
		msg.textContent = 'Skill updated!';
		setTimeout( function(){msg.textContent = '';}, 5000 );
		// Remove animation
		loader.className = '';
	});
}

function search() {
	var searchBox = document.getElementById('search-box');
	var resultDiv = document.getElementById('search-results');
	var searchString = searchBox.value;
	var skillSelect = document.getElementById('skill');
	
	removeChildren(resultDiv);

	for(let i = 1; i < skillSelect.length; i++) {
		let skillName = skillSelect.options[i].text;
		let skillId = skillSelect.options[i].value;
		if( skillName.toLowerCase().includes( searchString.toLowerCase() ) ) {
			let p = document.createElement('p');
			p.setAttribute('data-value', skillId);
			p.textContent = skillName;
			p.addEventListener('click', function() { addSearchedSkill(this);});
			resultDiv.appendChild(p);
		}
	}
	if(!resultDiv.hasChildNodes() && searchString !== '') {
		document.getElementById('add-missing-skill-btn').removeAttribute('disabled');
	} else {
		document.getElementById('add-missing-skill-btn').setAttribute('disabled','');
	}
}

function addMissingSkill() {
	var skillName = document.getElementById('search-box').value;
	var formData = new FormData();
	formData.append('skill-name', skillName);
	msg.textContent = 'Adding new skill...';
	xhr('admin/add-skill.php', formData, function (srvRes) {
		addSkill( skillName, srvRes );
		msg.textContent = 'New skill added!';
		setTimeout( function(){msg.textContent = '';}, 5000 );
	});
}

function addSearchedSkill( p ) {
	var skillName = p.textContent;
	var skillId = p.getAttribute('data-value');

	addSkill(skillName, skillId);

	// Remove skill from search results
	p.parentElement.removeChild(p);

	// Remove skill option from select
	var s = document.getElementById('skill');
	for( let i=1; i < s.length; i++ ){
		if( s.options[i].text === skillName ) {
			s.remove( i );
			break;
		}
	}
}

