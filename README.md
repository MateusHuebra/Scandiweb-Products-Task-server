# Scandiweb Products Task (server)
This is a little project asked by Scandiweb as a developer test assignment.
This is the back-end of the project, [click here](https://github.com/MateusHuebra/Scandiweb-Products-Task-client) for the front-end.

It contains a Product list page and Adding a product page, with three subtypes of products. It uses PHP (object oriented programming) and MySQL, in the back, and Vue.js, in the front.

## Server setup
- Copy Config.php.example to Config.php
- Fill the Config.php constants with your database info

### Database instalation
```
mysql create database products_scandiweb
```
```
mysql -u {user} –p products_scandiweb < database.sql
```
