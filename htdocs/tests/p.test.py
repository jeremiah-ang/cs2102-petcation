# Delete user whose pet had won and no services

from actions import *

browser = get_browser()

users = create_n_users(browser, 2)

login (browser, users[0][0], users[0][1])
pets = [['Pet 1', 'Dog', 'No Picture']]
create_pet(browser, pets)
topup_wallet(browser, [100])

relogin(browser, users, 1)
services = [['01/01/2019', '01/01/2020', 'Dog', 'Description']]
create_service(browser, services)

relogin(browser, users, 0)
place_bid(browser, '1', 'user1', 'Pet 1', 20)

relogin(browser, users, 1)
serviceid = "1"
bids = [["user0", "1"]]
accept_bids(browser, serviceid, bids)

loginadmin(browser)
remove_user(browser, 'user0')

# goto_all_user(browser)
browser.close()