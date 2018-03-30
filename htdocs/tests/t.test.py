# Delete users who has service

from actions import *

browser = get_browser()

users = create_n_users(browser, 2)

login(browser, users[1][0], users[1][1])
services = [['01/01/2019', '01/01/2020', 'Dog', 'Description']]
create_service(browser, services)

loginadmin(browser)
remove_user(browser, 'user1')

goto_all_user(browser)
browser.close()