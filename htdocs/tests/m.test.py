# Delete user who has no pet and no services

from actions import *

browser = get_browser()

users = create_n_users(browser, 1)

login (browser, 'admin', 'admin')

remove_user(browser, 'user0')
browser.close()

