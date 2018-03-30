# Update pet that has bidded

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
serviceid = "1"
bids = [["user1", "1"]]
accept_bids(browser, serviceid, bids)

relogin(browser, users, 1)
update_pet(browser, '1', 'new Pet name', 'Cat', 'No Picture')

browser.close()