from actions import *

browser = get_browser()

users = create_n_users(browser, 3)

login(browser, users[0][0], users[0][1])
dates = [['01/01/2019', '01/01/2020'],['01/01/2019', '01/05/2019'],['01/01/2019', '11/01/2019']]
for i in range(3):
	relogin(browser, users, i)
	topup_wallet(browser, [100,100])
	create_n_pets(browser, 3)
	create_n_service(browser, dates)



browser.close()