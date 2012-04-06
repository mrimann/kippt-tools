What's this all about?
----------------------
I started playing arount with kippt.com which looks promising as a successor for the beloved del.icio.us which (since acquired by that big company) lost a lot of the power that I liked. And since I already had a del.icio.us to MySQL-Backup script in place, I thought something like that needs to be possible with kippt.com, too.

And here we go: A rough first draft of a kippt.com backup script - maybe a migration tool from del.icio.us to kippt.com will follow.


Backing up all your kippt.com bookmarks to MySQL:
-------------------------------------------------

How to do?
..........
Just clone this repository, enter your local database credentials together with your kippt.com username and API-Token in the bottom of the script called "kipptBackup.php" and then run the following code on your shell:

    php -f kipptBackup.php

You can find your kipp.com API-Token by visiting the following "page" with your browser while being logged in: https://kippt.com/api/account

Feedback?
---------
Please yes - just send it to mario@rimann.org and let's see what I can make out of it. Of course nice "thank you" mails are appreciated most :-)


Found a bug or want an additional feature?
------------------------------------------
Don't worry, just open an issue on GitHub (https://github.com/mrimann/kippt-tools/issues) or even better: Fix it and contribute it back to the project. You can fork the sourcecode from the Git repository and send me a pull request as soon as you've finished (I have to find out how to work with this first, you might be the first contributor)