# Delete Service this is bidded

from actions import *

browser = get_browser()

users = create_n_users(browser, 2)

login(browser, users[0][0], users[0][1])
services = [['01/01/2019', '01/01/2020', 'Dog', 'Description']]
create_service(browser, services)

relogin(browser, users, 1)
pet = [['pet1', 'Dog', 'No']]
create_pet(browser, pet)
topup_wallet(browser, [100])
place_bid (browser, '1', 'user0', 'pet1', 5)

relogin(browser, users, 0)
remove_service(browser, '1')
browser.close()