![alt XOOPS CMS](https://xoops.org/images/logoXoops4GithubRepository.png)
## xrendertest module for [XOOPS CMS 2.5.11+](https://xoops.org)
[![Software License](https://img.shields.io/badge/license-GPL-brightgreen.svg?style=flat)](https://www.gnu.org/licenses/gpl-2.0.html)

[![Latest Pre-Release](https://img.shields.io/github/tag/DejaDingo/xrendertest.svg?style=flat)](https://github.com/DejaDingo/xrendertest/tags/)
[![Latest Version](https://img.shields.io/github/release/DejaDingo/xrendertest.svg?style=flat)](https://github.com/DejaDingo/xrendertest/releases/)

**xrendertest** module for [XOOPS CMS](https://xoops.org) is a simple module to test XoopsFormRenderer presentation
for XoopsThemeForm elements under any installed theme.
It was developed while upgrading my website to use Bootstrap 5.1.3 where the differences in Forms from Bootstrap 4.x
presented the most challenging code changes.

To facilitate switching between installed themes, enable the sysyem Themes block
at the top of the standard Xoops Left Column for all modules, all pages and all groups,
and set system preferences to make all installed themes selectable.
It is also helpful to set this module as the home module.

This is a testing module only.  There is no admin or any database tables.
The XrendertestUtility class contains methods to create form elements,
enclosing them in XrendertestElementWrapper objects (along with other controlling properties)
which are added to the form all at once just prior to rendering.
You can add tests for any element rendered by the XoopsFormRenderers for various themes.

Current and upcoming "next generation" versions of XOOPS CMS are crafted on GitHub at: https://github.com/XOOPS
