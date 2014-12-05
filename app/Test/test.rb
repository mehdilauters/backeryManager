# tests
# import
# mysql -u USER -pPWD -Ddatabae < app/Config/Schema/databaseCreate.sql && ruby ./app/Test/test.rb w HOST bakeryManager/
# http://mehdi/bakeryManager/config/initAcl
# http://mehdi/bakeryManager/users/add
# http://mehdi/bakeryManager/photos/add # rib
# http://mehdi/bakeryManager/companies/add
# # add company user
# http://mehdi/bakeryManager/users/add
# http://mehdi/bakeryManager/users/logout #logout root
# http://mehdi/bakeryManager/users/login # login companyManager
# http://mehdi/bakeryManager/photos/add
# http://mehdi/bakeryManager/shops/add

appRoot = ''
host ='localhost'
write = false #only allow reading tests TODO

rootUser ={}

if ARGV.length >= 1 && ARGV[0] == 'w'
  write = true
end

if ARGV.length >= 2
  host = ARGV[1]
end

if ARGV.length >= 3
  appRoot = ARGV[2]
end

if ARGV.length >= 4
  rootUser['email'] = ARGV[3].split(':')[0]
  rootUser['password'] = ARGV[3].split(':')[1]
end


BaseUrl = "http://#{host}/#{appRoot}"
puts BaseUrl
require "selenium-webdriver"
require "selenium-client"


Root = {'email' => 'root@lauters.fr',
		'password' => 'root',
		'name' => 'root'}
CompanyManagers  = [
                {'email' => 'companyManager1@lauters.fr',
		'password' => 'companyManager1',
		'name' => 'companyManager1'},
                  {'email' => 'companyManager2@lauters.fr',
                'password' => 'companyManager2',
                'name' => 'companyManager2'},
              ]

Users  = [
                {'email' => 'toto@testCompany1.fr',
                'password' => 'toto',
                'name' => 'toto'},
              ]


Photos = [{'path' => "#{Dir.pwd}/app/Test/data/shop.jpg",
	 'name' => 'ribTest',
         'description' => 'ribTest photo'},
         {'path' => "#{Dir.pwd}/app/Test/data/shop.jpg",
	 'name' => 'shoop',
         'description' => ''}
         ]
Companies = [
              { 'name' => 'testCompany1',
                'domain_name' => 'testCompany1.fr',
              'address' => '57 rue Lakanal',
              'phone' => '0687363854',
              'capital' => '7000',
              'siret' => '976456899',
              'title' => 'testCompany',
              'email' => 'company@testCompany1.fr',
            },
              { 'name' => 'testCompany2',
                'domain_name' => 'testCompany2.fr',
              'address' => '57 rue Lakanal',
              'phone' => '0687363854',
              'capital' => '7000',
              'siret' => '976456899',
              'title' => 'testCompany',
              'email' => 'company@lauters.fr',
            }
            ]
Shops = [{ 'name' => 'testShop',
            'address' => '57 rue Lakanal',
            'phone' => '0687363854',
            'description' => 'testShop',
	    'media' => 'shoop'
	   },
        { 'name' => 'testShop2',
            'address' => '55 rue Lakanal',
            'phone' => '0687443854',
            'description' => '1testShop',
	    'media' => 'shoop'
	   }
        ]

Emails = [
    {
     'company' => 'testCompany1',
     'email' => 'toto',
     'title' => 'test email',
     'password' => 'toto'
     },
  ]

ProductTypes = [
  {
   'name' => 'ProductTypeTest',
   'tva' => '5.5',
   'customerDisplay' => true,
   'description' => 'ProductTypeTest Description',
   'media' => 'shoop'
   },
  {
   'name' => 'ProductTypeTest 1',
   'tva' => '10.5',
   'customerDisplay' => true,
   'description' => 'ProductTypeTest 1 Description',
   'media' => 'ribtest'
   }
  
  ]

Products = [
  {
   'name' => 'ProductTest',
   'productType' => 'ProductTypeTest',
   'customerDisplay' => true,
   'description' => 'ProductTest Description',
   'media' => 'ribtest',
   'price' => '1.5',
   'unity' => true,
   'salesDisplay' => true,
   'productionAvailable' => true
   },
  {
   'name' => 'ProductTest 1',
   'productType' => 'ProductTypeTest 1',
   'customerDisplay' => true,
   'description' => 'ProductTest Description',
   'media' => 'ribtest',
   'price' => '2.5',
   'unity' => true,
   'salesDisplay' => true,
   'productionAvailable' => false
   },
  ]

time = Time.new

Dates = [
    '10/08/2014',
    '11/08/2014',
    '12/08/2014',
    '13/08/2014',
    '14/08/2014',
    '15/08/2014',
    "#{time.strftime('%d/%m/%Y')}",
  ]


Orders = [
  {
   'shop' => Shops[0]['name'],
   'dueDate' => '13/08/2014',
   'comment' => 'nazdar',
   'user' => 'toto',
   },
  {
   'shop' => Shops[1]['name'],
   'dueDate' => '15/08/2014',
   'comment' => 'nazdar #2',
   'user' => 'toto',
   },  
  ]

OrderItems = [
  {
   'product' => Products[0]['name'],
  'date' => '14/08/2014',
   'quantity' => 100,
   'comment' => 'coucou'
   },
  {
   'product' => Products[1]['name'],
  'date' => '15/08/2014',
   'quantity' => 150,
   'comment' => 'salut'
   },  
  
  ]

Accounts = [
  {
   'name' => 'testAccount',
   }
  ]

AccountsEntries = [
  {
   'date' => '30/11/2014',
   'name' => 'testEntrie0',
   'value' => -100
   },
   {
    'date' => '29/11/2014',
   'name' => 'testEntrie1',
   'value' => +100
   }
  ]

# Sales = [
#     {	
#       'date' => '10/08/2014'
#      
#      }
#   ]

driver = Selenium::WebDriver.for :firefox

def waitUntil(&elt)
  wait = Selenium::WebDriver::Wait.new(:timeout => 60) # seconds
  begin
    wait.until { elt.call }
  rescue
    puts "Error waiting #{elt}"
    raise 'waitError'
  end
end

def checkError(driver, raiseError = true)
  puts driver.title
  waitUntil { driver.find_element(:css => "body") }
  if driver.title.match /Error/
    puts driver.page_source
    raise "Error"
  end
  

  authError = true
  begin
    driver.find_element(:css => ".authError")
   rescue
     authError = false
  end
  if raiseError && authError
    raise "authError"
  end
  
    flashError = true
    flashElt = false
  begin
    flashElt = driver.find_element(:css => ".flashError")
   rescue
     flashError = false
  end
  if flashError
    puts flashElt.text
    if raiseError
      raise "flashError"
    end
  end
  return
end

def goto(driver, url, raise = true)
  puts url
  driver.navigate.to url
  sleep(1)
  checkError(driver, raise)
  
end


def login(driver, user)
  puts "login #{user.to_s}"
  waitUntil { driver.find_element(:id => "login") }

  logingLink = driver.find_element(:css => "#login > a");
  logingLink.click

  checkError(driver)

  waitUntil { driver.find_element(:id => "UserEmail") }
  email = driver.find_element(:id => "UserEmail")
  driver.action.send_keys(email, user['email']).perform
  
  pwd = driver.find_element(:id => "UserPassword")
  driver.action.send_keys(pwd, user['password']).perform
  logingSubmit = driver.find_element(:css => "#UserLoginForm > .submit > input");
  logingSubmit.click
  
  waitUntil { driver.find_element(:css => "#logout") }
  
end

def logout(driver)
  puts "logout"
  waitUntil { driver.find_element(:id => "logout") }

  logoutLink = driver.find_element(:css => "#logout > a");
  logoutLink.click
  checkError(driver)
end

def addUser(driver, user)
    puts "addUser #{user.to_s}"
  goto(driver, BaseUrl + "users/add")
    
    
    email = driver.find_element(:id => "UserEmail")
    driver.action.send_keys(email, user['email']).perform
    
    pwd = driver.find_element(:id => "UserPassword")
    driver.action.send_keys(pwd, user['password']).perform
    
    name = driver.find_element(:id => "UserName")
    driver.action.send_keys(name, user['name']).perform
    
  addSubmit = driver.find_element(:css => "#UserAddForm > .submit > input");
  addSubmit.click    
  checkError(driver)
end

def addPhoto(driver, photo)
    puts "addPhoto #{photo.to_s}"
  goto(driver, BaseUrl + "photos/add")
    name = driver.find_element(:id => "PhotoName")
    driver.action.send_keys(name, photo['name']).perform
    
    if(!File.file?(photo['path']))
       raise 'File to upload not found'
    end
      driver.find_element(:id => "PhotoUpload").send_keys(photo['path'])
#     description = driver.find_element(:id => "PhotoDescription")
#     driver.action.send_keys(description, photo['description']).perform
      driver.execute_script "tinyMCE.activeEditor.setContent('Replace with your text')"
    
  addSubmit = driver.find_element(:css => "#PhotoAddForm > .submit > input");
  addSubmit.click
  checkError(driver)
end


def addCompany(driver, company)
    puts "addCompany #{company.to_s}"
  goto(driver,BaseUrl + "companies/add")
    
    
    driver.find_element(:id => "CompanyEmail").send_keys(company['email'])
    driver.find_element(:id => "CompanyDomainName").send_keys(company['domain_name'])
    driver.find_element(:id => "CompanyAddress").send_keys(company['address'])
    driver.find_element(:id => "CompanyName").send_keys(company['name'])
    driver.find_element(:id => "CompanyPhone").send_keys(company['phone'])
    driver.find_element(:id => "CompanyCapital").send_keys(company['capital'])
    driver.find_element(:id => "CompanySiret").send_keys(company['siret'])
    driver.find_element(:id => "CompanyTitle").send_keys(company['title'])
    
    driver.find_element(:css => "#CompanyAddForm > .submit > input").click;
    checkError(driver)
end

def addProductType(driver, productType)
    puts "addProductType #{productType.to_s}"
  goto(driver,BaseUrl + "productTypes/add")
    
  driver.find_element(:id => "ProductTypeMediaId").send_keys(productType['media'])
  driver.find_element(:id => "ProductTypeName").send_keys(productType['name'])
  driver.find_element(:id => "ProductTypeTva").send_keys(productType['tva'])
  driver.execute_script "tinyMCE.activeEditor.setContent('Replace with your text')"  
    
  if(productType['customerDisplay'])
    driver.find_element(:id => "ProductTypeCustomerDisplay").click
  end
    driver.find_element(:css => "#ProductTypeAddForm > .submit > input").click;
    
    checkError(driver)
end

def addProduct(driver, product)
    puts "addProduct #{product.to_s}"
  goto(driver,BaseUrl + "products/add")
    
  driver.find_element(:id => "ProductMediaId").send_keys(product['media'])
  driver.find_element(:id => "ProductProductTypesId").send_keys(product['productType'])
  driver.find_element(:id => "ProductName").send_keys(product['name'])
  driver.find_element(:id => "ProductPrice").send_keys(product['price'])
  
  driver.execute_script "tinyMCE.activeEditor.setContent('Replace with your text')"  
    
  if(product['customerDisplay'])
    driver.find_element(:id => "ProductCustomerDisplay").click
  end


  if(product['salesDisplay'])
    driver.find_element(:id => "ProductProductionDisplay").click
  end
  
    if(product['productionAvailable'])
    driver.find_element(:id => "ProductDependsOnProduction").click
  end
  
  if(product['unity'])
    driver.find_element(:id => "ProductUnity").click
  end
  
    driver.find_element(:css => "#ProductAddForm > .submit > input").click;
    
    checkError(driver)
end


def addShop(driver, shop)
  puts "addShop #{shop.to_s}"
    goto(driver,BaseUrl + "shops/add")
    driver.find_element(:id => "ShopName").send_keys(shop['name'])
    driver.find_element(:id => "ShopAddress").send_keys(shop['address'])
    driver.find_element(:id => "ShopPhone").send_keys(shop['phone'])
    driver.find_element(:id => "ShopMediaId").send_keys(shop['media'])
  driver.execute_script "tinyMCE.activeEditor.setContent('#{shop['description']}')"
  driver.find_element(:css => "#ShopAddForm > .submit > input").click;
  checkError(driver)
end

def addOrder(driver, order)
  puts "addOrder #{order.to_s}"
  goto(driver,BaseUrl + "orders/add")
  driver.find_element(:id => "OrderShopId").send_keys(order['shop'])
  driver.find_element(:id => "OrderUserId").send_keys(order['user'])
  driver.find_element(:id => "OrderDeliveryDate").send_keys(order['dueDate'])
  driver.execute_script "tinyMCE.activeEditor.setContent('#{order['comment']}')"
  driver.find_element(:css => "#OrderAddForm > .submit > input").click;
  checkError(driver)
end


def selectFirstOrder(driver, email = false)
  puts "selectFirstOrder"
  
  goto(driver,BaseUrl + "orders")
  
  goto(driver,
    driver.find_element(:css => "#ordersIndexTable .actions a[title=Voir]").attribute("href")
      )
  
  if email
    goto(driver,BaseUrl + "orders")
    goto(driver,
      driver.find_element(:css => "#ordersIndexTable .actions a[title=Email]").attribute("href")
        )
  end
end


 def selectFirstShop(driver)
  puts "selectFirstShop"
  goto(driver,BaseUrl )
  goto(driver,
    driver.find_element(:css => ".shopPreview .title a").attribute("href")
      )
  

  
end

def selectFirstProduct(driver)
  puts "selectFirstProduct"
  
  goto(driver,BaseUrl + "products" )
  
  
  goto(driver,
    driver.find_element(:css => ".slate a").attribute("href")
  )
  
end




  
def addItem(driver, item)
  puts "AddItem #{item.to_s}"
  
  driver.find_element(:css => ".addItem > a").click;
  waitUntil { driver.find_element(:css => "#OrderedItemProductId") }
  
  
  driver.find_element(:id => "OrderedItemProductId").send_keys(item['product'])
  driver.find_element(:id => "OrderedItemCreated").clear
  driver.find_element(:id => "OrderedItemCreated").send_keys(item['date'])
  driver.find_element(:id => "OrderedItemQuantity").send_keys(item['quantity'])
  driver.execute_script "tinyMCE.activeEditor.setContent('#{item['comment']}')"
  driver.find_element(:css => "#OrderedItemAddForm > .submit > input").click;
  
  checkError(driver)
  
end

def deleteFisrtSale(driver)
  puts "deleteSales"
  goto(driver, BaseUrl + "sales/view")
  waitUntil{ driver.find_element(:css => "#deleteSale input[type=submit]") }
  driver.find_element(:css => "#deleteSale input[type=submit]").click
end

def addAccount(driver, account)
  puts "addAccount #{account}"
  goto(driver,BaseUrl + "account_management/accounts/add")
  closeIntro(driver)
  driver.find_element(:css => "#AccountName").clear()
  driver.find_element(:css => "#AccountName").send_keys(account['name'])
  driver.find_element(:css => "#AccountAddForm input[type=submit]").click
end

def selectFirstAccount(driver)
  puts "selectFirstAccount"
  goto(driver,BaseUrl + "account_management/")
  
  goto(driver,
    driver.find_element(:css => "a.view").attribute("href")
  )
  
end

def addAccountEntry(driver, accountEntry, js = true)
  puts "addAccountEntry #{accountEntry}"
    
  closeIntro(driver)
  if(js)
    driver.find_element(:css => "td.AccountEntryDate input").clear()
    driver.find_element(:css => "td.AccountEntryDate input").send_keys(accountEntry['date'])
    driver.find_element(:css => "td.AccountEntryName input").send_keys(accountEntry['name'])
    driver.find_element(:css => "td.AccountEntryValue input").send_keys(accountEntry['value'])
    driver.find_element(:css => ".saveButton").click
  else
    goto(driver,
      driver.find_element(:css => "#addEntry").attribute("href")
    )
    driver.find_element(:css => "#AccountEntryDate").clear()
    driver.find_element(:css => "#AccountEntryDate").send_keys(accountEntry['date'])
    driver.find_element(:css => "#AccountEntryName").send_keys(accountEntry['name'])
    driver.find_element(:css => "#AccountEntryValue").send_keys(accountEntry['value'])
    driver.find_element(:css => "#AccountEntryAddForm input[type=submit]").click
  end
end



def addSales(driver, dte)
    puts "addSales"
    goto(driver,BaseUrl + "sales/add")
    closeIntro(driver)
    driver.find_element(:css => "#dateSelectValue").clear()
    driver.find_element(:css => "#dateSelectValue").send_keys(dte)
    driver.find_element(:css => "#dateSelect").click
  i = 0
  elts = driver.find_elements(:css => "li.produced input[type=text]")
  elts.each{|e|
            e.send_keys(i * 2)
           i += 1
            }
  i = 0
  elts = driver.find_elements(:css => "li.lost input[type=text]")
  elts.each{|e|
            e.send_keys(i)
           i += 1
            }
  i = 0
  elts = driver.find_elements(:css => ".comment textarea")
  elts.each{|e|
            e.send_keys("comment #{i}")
           i += 1
            }
  driver.find_element(:css => "#salesAdd input[type=submit]").click
  
  checkError(driver)
end

def addResult(driver, dte)
    puts "addResult"
    goto(driver,BaseUrl + "results/add")
    closeIntro(driver)
    driver.find_element(:css => "#dateSelectValue").clear()
    driver.find_element(:css => "#dateSelectValue").send_keys(dte)
    driver.find_element(:css => "#dateSelect").click
    eltsContainer = driver.find_elements(:css => ".resultsShop")
    chopId = 0
    eltsContainer.each{|ec|
      u0 = 100
      r = 140 + chopId*10
      i = 0
      val = 0
      elts = ec.find_elements(:css => ".paymentResults input[type=text]")
      elts.each{|e|
               val = u0 + i * r
		e.send_keys(val)
               i += 1
		}                     
                      
	j = 0
                      
	  elts = ec.find_elements(:css => ".productTypesResults input[type=text]")
	  sum = (i)*(u0+val)/2
                      
                      
	  jStep = sum / elts.length
	  elts.each{|e|
		    j = jStep
		    e.send_keys(j)
		    }  
           chopId += 1
	}
  
  
  driver.find_element(:css => "#resultsAdd input[type=submit]").click
  
  checkError(driver)
end

def detectChart(driver)
  waitUntil { driver.find_element(:css => ".jqplot-target") }
end

def createEmail(driver, email)
  goto(driver,BaseUrl + "emails/add")
  print "add email #{email}"
  driver.find_element(:id => "EmailCompanyId").send_keys(email['company'])
  driver.find_element(:id => "EmailEmail").send_keys(email['email'])
  driver.find_element(:id => "EmailTitle").send_keys(email['title'])
  driver.find_element(:id => "EmailPassword").send_keys(email['password'])
  driver.find_element(:css => "#EmailAddForm input[type=submit]").click
  checkError(driver)
  waitUntil { driver.find_element(:css => "img.ok") }
end

def closeIntro(driver)
    puts "closeIntro"
    begin
      driver.execute_script "intro.exit()"
    rescue
    end
end

def setCompany(driver, id)
  puts "=============== change to company #{id} =============="
  f = File.new('app/tmp/companyId', 'w')
  f.write(id)
  f.close
end

def testAccountPlugin(driver)
  addAccount(driver, Accounts[0])
      selectFirstAccount(driver)
      total = 0
      js = false
      AccountsEntries.each{|entry|
             total += entry['value']
             addAccountEntry(driver, entry, js)
             js = true
      }
      (0..10).each{|i|
              v = 100*i
             addAccountEntry(driver, {'date'=>AccountsEntries[0]['date'],
                                      'name' => "test_#{i}",
                                      'value'=> v})
             total += v
                   }
      jsTotal = driver.find_element(:css => "#total").text().to_i
      if(jsTotal != total || jsTotal == AccountsEntries[0]['value'])
        raise "totals are differents or not expected #{jsTotal} / #{total}"
      end
end


  if rootUser == {}
    rootUser = Root
  end

# begin
  if write
    goto(driver, BaseUrl + "config/initAcl")
    addUser(driver, rootUser);
     Companies.each_with_index { |company,companyId|
      company = Companies[companyId]
      login(driver, rootUser);
      addPhoto(driver, Photos[0]);
      addCompany(driver, company)
      setCompany(driver, companyId+1)
      addUser(driver, CompanyManagers[companyId]);
      createEmail(driver, Emails[0])
      logout(driver)
      login(driver, CompanyManagers[companyId]);
      
      testAccountPlugin(driver)
      
      
      
      addUser(driver, Users[0]);
  #     goto(driver, BaseUrl + "config/setCompany/#{companyId}")
      addPhoto(driver, Photos[1]);
      addShop(driver, Shops[0])
      addShop(driver, Shops[1])
      addProductType(driver, ProductTypes[0])
      addProductType(driver, ProductTypes[1])
      addProduct(driver, Products[0])
      addProduct(driver, Products[1])
      
      Dates.each{
        |dte|
        addSales(driver,dte)
        addResult(driver, dte)
        }
      goto(driver, BaseUrl + "sales")
      goto(driver, BaseUrl + "sales/stats")
      deleteFisrtSale(driver)
      goto(driver, BaseUrl + "results/stats")
      goto(driver, BaseUrl)
      detectChart(driver)
      addOrder(driver, Orders[0])
      selectFirstOrder(driver)
      addItem(driver, OrderItems[0])
      selectFirstOrder(driver)
      addItem(driver, OrderItems[1])
      selectFirstOrder(driver, true)
#       waitUntil { driver.find_element(:css => "#emailPreview") }
      
      addOrder(driver, Orders[1])
      logout(driver)
    }
  end
    goto(driver, BaseUrl)
    login(driver, rootUser);
    goto(driver, BaseUrl + "users")
    goto(driver, BaseUrl + "users/add")
    goto(driver, BaseUrl + "shops/add")
    
    goto(driver, BaseUrl + "productTypes")
    goto(driver, BaseUrl + "productTypes/add")
    
    goto(driver, BaseUrl + "products")
    goto(driver, BaseUrl + "products/add")
    
    goto(driver, BaseUrl + "sales")
    goto(driver, BaseUrl + "sales/add")
    goto(driver, BaseUrl + "sales/stats")
    goto(driver, BaseUrl + "sales/view")
    
    goto(driver, BaseUrl + "results/")
    goto(driver, BaseUrl + "results/add")
    goto(driver, BaseUrl + "results/stats")
    
    goto(driver, BaseUrl + "photos/")
    goto(driver, BaseUrl + "photos/add")
    
    goto(driver, BaseUrl + "orders")
    goto(driver, BaseUrl + "orders/add")
    
    selectFirstOrder(driver)
    selectFirstShop(driver)
    selectFirstProduct(driver)
    detectChart(driver)
  
# rescue Exception => e
#   puts "##### ERROR #####"
#   puts driver.page_source
#   puts e.backtrace
#   raise e
# end

