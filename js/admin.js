'use strict';

// Place that displays status message
var msg = document.getElementById('upload-message');
// Div that displays the loading animation
var loader = document.getElementById('loader');

function addSkill() {
	var skill = document.getElementById('skill');
	var option = document.createElement("option");
	var newSkillName = document.getElementById('newSkill').value;

	var formData = new FormData();
	formData.append('skill-name', newSkillName);

	msg.textContent = 'Adding new skill...';
	loader.className = 'loader';

	xhr('add-skill.php', formData, function (srvRes) {
		option.setAttribute("value", srvRes);
		option.textContent = newSkillName;
		skill.appendChild(option);
		loader.className = '';
		msg.textContent = 'New skill added!';
		setTimeout( function(){msg.textContent = '';}, 5000 );
	});

}

function addSkillToSearchFor(sn, si) {
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
	select.id = "skill"+skillId;
	select.setAttribute("name","skill"+skillId);
	select.addEventListener('change', fetchUsernames);

	// Create options for the select
	for( let i = 0; i <= 5; i++ ) {
		let option = document.createElement("option");
		option.setAttribute("value", i);
		option.textContent = i;
		if( i == 0 )
			option.setAttribute("selected", "");
		select.appendChild(option);
	}

	// Text that will appear in label
	var content = document.createTextNode(skillName);
	label.appendChild(content);

	//Create the delete button
	var deleteBtn = document.createElement("button");
	deleteBtn.setAttribute("class","");
	deleteBtn.addEventListener('click',function() {
		var parentElement = document.getElementById('skill'+skillId).parentElement.parentElement;
		removeChildren(parentElement);
		parentElement.parentElement.removeChild(parentElement);
		fetchUsernames();

		var option = document.createElement('option');
		option.setAttribute("value", skillId);
		option.textContent = skillName;
		var s = document.getElementById('skill');
		for( let i = 1; i < s.length; i++ ){
			if( s.options[i].text.toLowerCase() > skillName.toLowerCase() ) {
				s.insertBefore(option, s.options[i]);
				break;
			}
			if( i == s.length-1 )
				s.appendChild(option);
		}
	});
	deleteBtn.textContent = "X";

	// Add all the new elements
	div.appendChild(label);
	di.appendChild(select);
	di.appendChild(deleteBtn);
	di.appendChild(document.createElement("br"));
	div.appendChild(di);
	select.className ='fade';
	skillDiv.insertBefore(div, skillDiv.firstChild);
	//skillDiv.appendChild(div);

	// Remove the chosen skill from the list of all skills
	if(arguments.length < 2) {
		skill.remove(skill.selectedIndex);
	}

	fetchUsernames();

}

function deleteSkill() {
	msg.textContent = 'Deleting skill...';
	loader.className = 'loader';
	var skill = document.getElementById('skill');
	var skillId = skill.options[skill.selectedIndex].value;
	skill.remove(skill.selectedIndex);

	var formData = new FormData();
	formData.append('skill-id', skillId);

	xhr('delete-skill.php', formData, function (srvRes) {
		loader.className = '';
		msg.textContent = 'Skill deleted!';
		setTimeout( function(){msg.textContent = '';}, 5000 );
	});
}

function fetchUsernames() {
	loader.className = 'loader';
	msg.textContent = 'Searching...';
	var skills = document.getElementById('skills');
	var selectedSkills = skills.getElementsByTagName('select');

	// Add selected skills to a list...
	var list = [];
	for( let i = 0; i < selectedSkills.length; i++ ) {
		let skill = selectedSkills[i];
		let skillLevel = skill.options[skill.selectedIndex].text;
		let skillId = skill.getAttribute('id').slice(5);
		if( skillLevel !== '0')
			list.push([skillId, skillLevel]);
	}

	// ...and send it
	var jsonString = JSON.stringify(list);
	var formData = new FormData();
	formData.append('list', jsonString);

	xhr('search.php', formData, function (srvRes) {
		var resultsDiv = document.getElementById('results');

		// Remove previous results
		while( resultsDiv.firstChild ) {
			resultsDiv.removeChild(resultsDiv.firstChild);
		}

		// If no results
		if(! srvRes.trim()) {
			let p = document.createElement("p");
			p.appendChild(document.createTextNode("No results"));
			resultsDiv.appendChild(p);
			loader.className = '';
			msg.textContent = '';
			return;
		}

		// Create links to search results
		var results = srvRes.split(/\s+/g);
		for(let i=0; i < results.length; i++) {
			let a = document.createElement("a");
			a.setAttribute('href','view-profile.php?username='+results[i]);
			a.appendChild(document.createTextNode(results[i]));
			resultsDiv.appendChild(a);
			resultsDiv.appendChild(document.createElement("br"));
		}

		loader.className = '';
		msg.textContent = '';
		setTimeout( function(){msg.textContent = '';}, 5000 );
	});
}

function search() {
	var searchBox = document.getElementById('search-box');
	var resultDiv = document.getElementById('search-results');
	var searchString = searchBox.value;
	var skillSelect = document.getElementById('skill');
	
	removeChildren(resultDiv);

	if(!searchString) {
		return;
	}

	for(let i = 1; i < skillSelect.length; i++) {
		let skillName = skillSelect.options[i].text;
		let skillId = skillSelect.options[i].value;
		if( skillName.toLowerCase().includes( searchString.toLowerCase() ) ) {
			let p = document.createElement('p');
			p.setAttribute('value', skillId);
			p.textContent = skillName;
			p.addEventListener('click', function() { addSearchedSkill(this);});
			resultDiv.appendChild(p);
		}
	}
}

function addSearchedSkill( p ) {
	var skillName = p.textContent;
	var skillId = p.getAttribute('value');

	addSkillToSearchFor(skillName, skillId);

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

