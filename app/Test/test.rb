BaseUrl = 'http://mehdi/bakeryManager/'
require "selenium-webdriver"


LoginEmail = 'demo@lauters.fr'
LoginPwd = 'demo'

driver = Selenium::WebDriver.for :firefox
driver.navigate.to BaseUrl

# wait for a specific element to show up




def login(driver)
  wait = Selenium::WebDriver::Wait.new(:timeout => 60) # seconds
  wait.until { driver.find_element(:id => "login") }

  logingLink = driver.find_element(:css => "#login > a");
  logingLink.click


  wait.until { driver.find_element(:id => "UserEmail") }
  email = driver.find_element(:id => "UserEmail")
  driver.action.send_keys(email, LoginEmail).perform
  
  pwd = driver.find_element(:id => "UserPassword")
  driver.action.send_keys(pwd, LoginPwd).perform
 logingSubmit = driver.find_element(:css => "#UserLoginForm > .submit > input");
  logingSubmit.click
  
end



login(driver)