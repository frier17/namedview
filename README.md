# README
**NamedView Laravel Facade**
A Laravel facade for automatically loading view template files mapped or registered against a controller

NamedView was designed to quickly map controller function calls to the registered template or view file. 
Conventional means of rendering view template in Laravel outputs the view through call to the #view helper# or call to View Facade. The NamedView extends this behaviour enabling developers define a number of template files ahead of time (similar to the way routes are defined in routes.php) and then allow the Controller simply run calling NamedView render method to do its magic. 

**How it works**
NamedView automatically identifies function calls, and lookup a registry of view file which should be rendered for the called method and returns the appropriately rendered template file with passed data, user preferences, permissions etc.

Working together with a ControllerTrait (handles default behaviour for index, edit, update, delete, and detail), the NamedView can load files stored following a naming convention, thereby ease development and save time.

Feel free to try out the concept and share your thoughts if any.

Do not use this Facade in production environment. NamedView was designed for a small project and this beta version is yet to undergo rigorous test. I have published it here primarily for evaluation purposes.
