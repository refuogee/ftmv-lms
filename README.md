# FTMV LMS

## Description
The acronym here reads learnER management system as opposed to learnING management system. 

This plugin faciliates the creation of programmes, facilitators and students associated with said programmes and courses which are instances of programmes that run at designated intervals. The plugin also enables users to restrict pages or posts and assign them to programmes thus creating the content which students then have access to while not being able to view content that they are not assigned to.

Initially created as a solution for a client's needs. I started by creating code in a child theme's function file. While this served the purpose I knew that this was not best practice and wanting to expand my WordPress knowledge as well as PHP knowledge I sought to turn the code into a plugin. 

I followed along with [Ratko Solaja's](https://www.toptal.com/wordpress/ultimate-guide-building-wordpress-plugin) amazing walkthrough and using his boilerplate code I built my plugin.

This plugin is not production ready. 

## Installation
Unzip the file into your WordPress plugin folder and you can install it from WordPress admin. 

## Features:
- Create top level programme
- Create Facilitators for a programme
- Create courses for a programme
- Create students attached to these courses
- Restrict pages or posts and assign them to programmes
- Students can then only view pages for programmes they were created under

## Next Steps:
- Emailing newly created users has not been handled 
- A choice needs to be made for what happens to user data on course / programme / plugin deletion and then implemented
- Better structuring of code
