What's this all about?
----------------------
I started playing around with kippt.com which looks promising as a successor for the beloved del.icio.us which (since acquired by that big company) lost a lot of the power that I liked. And since I already had a del.icio.us to MySQL-Backup script in place, I thought something like that needs to be possible with kippt.com, too.

And here we go: A rough first draft of a kippt.com backup script - maybe a migration tool from del.icio.us to kippt.com will follow.


Backing up all your kippt.com bookmarks to MySQL:
-------------------------------------------------

How to do?
..........
1. Clone this repository

2. Create a database with a table called "clips" (see database schema file in the cloned directory)

3. Copy config.sample.php to config.php and enter your local database credentials together with your kippt.com username and API-Token
   You can find your kippt.com API-Token by visiting the following "page" with your browser while being logged in: https://kippt.com/api/account

4. Run the script : php -f kipptBackup.php


Contributors
------------
The following persons have supported the kippt-tools development:

- Sébastien Wains, https://github.com/sebw
	- Fetching of clips in batches
	- Showing some nice progress

Thanks for your contribution!

Feedback?
---------
Please yes - just send it to mario@rimann.org and let's see what I can make out of it. Of course nice "thank you" mails are appreciated most :-) For other ideas on how to support me, just have a look at http://rimann.org/support


Found a bug or want an additional feature?
------------------------------------------
Don't worry, just open an issue on GitHub (https://github.com/mrimann/kippt-tools/issues) or even better: Fix it and contribute it back to the project. You can fork the sourcecode from the Git repository and send me a pull request as soon as you've finished.