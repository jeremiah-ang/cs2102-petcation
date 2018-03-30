# Delete user who has pet but not bidded and no services

from actions import *

browser = get_browser()

users = create_n_users(browser, 1)

login (browser, users[0][0], users[0][1])
pets = [['Pet 1', 'Dog', 'No Picture']]
create_pet(browser, pets)

loginadmin(browser)
remove_user(browser, 'user0')
browser.close()

