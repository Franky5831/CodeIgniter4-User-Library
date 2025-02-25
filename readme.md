# Codeigniter 4 User Management Library

This is a library developed for Codeigniter 4 that will make the management of users in your application easier and more secure.

The library will automatically manage the registration and login process, with security measures in pace.

## Security measures:
- [x] Xss
- [x] SQL Injections
- [x] Session Hijacking
- [x] Captchas
- [ ] Brute force attacks


Please remember that this library is still being developed, some features might not be already available and others might not work at all 😊.


## How to install the library:
1. Add the following code block to the composer.json file:
```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:franky5831/CI4-PCKG-UserLib.git"
    }
],
```
Since this is a private repository, you need to add that line to your composer.json file.

2. Run the following command in your terminal:
```bash
composer require franky5831/ci4-pckg-test
```
