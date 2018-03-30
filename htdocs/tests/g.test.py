# Delete Service this is not bidded

from actions import *

browser = get_browser()

users = create_n_users(browser, 2)

login(browser, users[0][0], users[0][1])
services = [['01/01/2019', '01/01/2020', 'Dog', 'Description']]
create_service(browser, services)
remove_service(browser, '1')
browser.close()