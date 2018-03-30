#Delete user who has no pets but have service but is not bidded

from actions import *

browser = get_browser()

users = create_n_users(browser, 2)

login(browser, users[0][0], users[0][1])
services = [['01/01/2019', '01/01/2020', 'Dog', 'Description']]
create_service(browser, services)

loginadmin(browser)
remove_user(browser, 'user0')
browser.close()