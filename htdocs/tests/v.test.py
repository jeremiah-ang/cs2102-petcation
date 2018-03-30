# Update pet that has bidded

from actions import *

browser = get_browser()

users = create_n_users(browser, 2)

login(browser, users[0][0], users[0][1])
topup_wallet(browser, [500])
update_user(browser, "user0", "passwordnew", "new full name", "01/09/1994", "new address")

browser.close()