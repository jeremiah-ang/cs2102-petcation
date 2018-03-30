from actions import *

browser = get_browser()

# Create users
print (">> Creating Users ")
users = create_n_users(browser, 3)
print (">> 3 User Created ")

'''

	Have User0 Create 3 Service
		- 1 service thats legit
		- 1 service where end date < start date
		- 1 service where end date < today

'''
print (">> User0: Creating 4 Service ")
dates = [["01/01/2019", "31/12/2019", "Dog", "Service Description 1"], 
			["01/01/2019", "30/11/2019", "Dog", "Service Description 2"], 
			["01/01/2019", "31/11/2019", "Dog", "INVALID Date (November don't have day 31)"], 
			["31/12/2019", "31/12/2018", "Dog", "INVALID DATE (Start date > End date)"], 
			["31/12/2016", "31/12/2017", "Dog", "INVALID DATE (Dates already over)"]]
print_listlist(dates)

login (browser, users[0][0], users[0][1])
create_service(browser, dates)



print (">> User1: Create 2 Pet, Top up 2 times, Bid 3 times! ")
pets = [['pet1', 'Dog', "no"], 
		['pet2', 'Dog', "no"]]
print_listlist(pets)
amounts = [500, 
			500]
print_list(amounts)
bids = [["1", "user0", "pet1", 50], 
		["1", "user0", "pet1", 30], 
		["1", "user0", "pet1", 50], 
		["1", "user0", "pet2", 50]]
print_listlist(bids)

relogin(browser, users, 1)
create_pet_wallet_bid (browser, pets, amounts, bids)





print (">> User2: Create 2 Pet, Top up 2 times, Bid 2 times! ")
pets = [['pet3', 'Dog', "no"], ['pet4', 'Dog', "no"]]
print_listlist(pets)
amounts = [500, 500]
print_list(amounts)
bids = [["1", "user0", "pet3", 50], ["1", "user0", "pet4", 30]]
print_listlist(bids)

relogin(browser, users, 2)
create_pet_wallet_bid (browser, pets, amounts, bids)


print (">> User0: Accpet Bids")
serviceid = "1"
print_single(serviceid)
bids = [["user1", "1"], ["user2", "2"]]
print_listlist(bids)

relogin(browser, users, 0)
accept_bids (browser, serviceid, bids)

exit()

print (">> User1: Place Bids for pet2")
bids = [["2", "user0", "pet2", 100]]
print_listlist(bids)
relogin(browser, users, 1)
place_bids(browser, bids)


print (">> User1: Remove Pet2")
pet_to_remove = "pet2"
print_single(pet_to_remove)
remove_pet(browser, pet_to_remove)

print (">> User 1: Place bid")
bids = [["2", "user0", "pet1", 100]]
print_listlist(bids)
place_bids(browser, bids)

print (">> User 0: Delete Service!")
service_to_remove = '2'
print_single(service_to_remove)
relogin(browser, users, 0)
remove_service(browser, service_to_remove)

browser.close()