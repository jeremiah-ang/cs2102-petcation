# Update pet that has not bidded

from actions import *

browser = get_browser()

users = create_n_users(browser, 2)

login(browser, users[0][0], users[0][1])
pet = [['pet1', 'Dog', 'No']]
create_pet(browser, pet)

update_pet(browser, '1', 'new Pet name', 'Cat', 'No Picture')
browser.close()