[SegMetrics](https://segmetrics.io/) Custom URL Shortener
==========================================================
_[SegMetrics](https://segmetrics.io) provides a single dashboard to understand the return you get on your marketing dollars.
Get 100% clarity on where your leads come from, how they act, and how much your marketing is really worth. **[Get started today!](https://segmetrics.io)**_

---

The SetMetrics customer URL Shortener is an easy way to set up your own short URLs 
without relying on third parties like Bit.ly and Goo.gl that strip valuable UTM and source information from your links.

The URL Shortener has no external requirements and can run either from a top-level domain (like [mtr.cx](http://mtr.cx)), subdomain ([seg.mtr.cx](https://seg.mtr.cx)) or subfolder ([mtr.cx/to/](https://mtr.cx/to/))


Installation
---------------------------------------------------------
The SegMetrics URL Shortener is written in plain PHP, and does not use a database.
It has no external requirements other than being able to run PHP 5.6 or greater.
You can install this alongside WordPress, or any other site, as long as you install it in a sub-folder.
 
1. To get started with the URL Shortener on your server, [Download the Latest Release](https://github.com/segmetrics/url-shortener/archive/master.zip)
from the **Download** button, above.

2. If you are using a top-level domain, or a subdomain, upload the files to the root directory on your server, like below.
If you want to use a subfolder, then upload the files to your subfolder.
```
/
admin/
    /assets
    index.php
.htaccess
index.php
LICENSE
README.md
``` 

3. Open your browser and go to the folder where you uploaded the files. This will start the setup process.

4. Choose a Username and Password for the URL Shortener. This will be required to log in later.
Your password is hashed and stored securely on the server.

5. Log in with the Username and Password that you just created. **Congratulations! You're done!**


FAQ
---------------------------------------------------------

**How do I reset my password?**
Delete the `config.cfg` file in the `data` directory and open the site to re-run the setup screen.

Dependencies
---------------------------------------------------------
- [PHP >= 5.6](https://php.net/)