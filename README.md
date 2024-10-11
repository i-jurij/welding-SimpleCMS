SimpleCMS    
Простая система управления контентом для сайта.    
Возможности:    
форма заказа обратного звонка,    
записи на услугу к специалисту,    
страницы с описанием услуг и расценками,    
отдельная страница с расценками,    
страница с информацией об организации,   
карта проезда,    
галерея фото,    
прочие страницы.    
Все вышеперечисленное доступно к редактированию из админки.    
Клиенты и записи вносятся в базу, очищается база автоматически раз в два года    
(очистку можно отключить или изменить только в коде).    
Клиент может управлять своими записями, если придумает и сохранит пароль во время записи на услугу.    
При этом клиент не является пользователем приложения.    
При создании мастера пользователь создается автоматически со статусом "user"    
и именем по номеру телефона (+01112223344),    
email - +01112223344@com.com,     
пароль - "password". Пароль каждый пользователь может изменить.     

Возможно создавать страницы с нуля, при этом есть три возможности:    
simple page "yes"- одиночная страница, без вложенных страниц, содержимое страницы хранится      
в колонке "content" таблицы "pages", представление создавать не нужно,    
simple page "no"- страница, для которой будут созданы контроллер, модель, представление,   
к примеру "Персональные данные" - это simple page = "yes", а "Перезвоните мне" и "О нас" - simple page = 'no'.    
service page "yes" - страница с услугами, создавать лучше из отдельного меню в админке, там же есть пункты     
для добавления категорий услуг и самих услуг.    
Но проще просто вносить изменения в имеющиеся страницы, создавая только страницы услуг и внося расценки.


## Start
Directory Permissions   
Laravel will need to write to the bootstrap/cache and storage directories, so you should ensure the web server process owner has permission to write to these directories.   

You need to call 
```php artisan key:generate```
is following a clone of a pre-created Laravel project.

Install php > 8.1.   
Install sql (mysql, mariadb).   
Run php and sql service.   
Configure your DB in application into "app_root_dir/config/database.php": type, user, password etc.  
Configure your DB user and her privilegies in phpmyadmin eg. 
Run terminal (console). 
Cd into applications root directory.   
Execute in console:   
```composer install```  
```npm install```  
```php artisan storage:link```
Then: 
if you have DB dump - restore DB from dump and   
```php artisan serve```   
else   
```php artisan migrate```   
Create user with admin rights with status "admin" (in phpmyadmin eg) then   
```php artisan serve``` 

For css and js load:   
for development or localhost```npm run dev```   
for deploy ```npm run build``` then ```git pull``` on server into site root dir.   

## Deploy to production  
```php artisan optimize```
or   
```php artisan optimize:clear```
Then   
```php artisan config:cache```  
Detail: https://laravel.com/docs/10.x/deployment   


## Work
### PAGES edit
/admin/page/create - creating client page:

alias - single lowcase word only letters

single_page = 'yes' - if you only need to get data from table Pages from DB and load data into default view (page_template.blade.php).
single_page = 'no' - if you need to have controller and model and migration and view for page (or alias or title for menu path from db tables)

service_page = 'yes' - if you need to create page with services CRUD (resource controler)
As models for service page - model Pages, model Categories, model Services
As view - resources/client_manikur/client_pages/service_page.blade.php

After creating service page you can add categories and services to page.
Also admin panel has items for creating and editing categories and services.

After creating the page - open files in your editor or IDE and edit.
Also put route in routes/web.php.

### SERVICE PAGE edit
In form service_page = yes.
An entry will be created in the database.

### SERVICE edit
First create the services, then the masters.

### MASTERS edit   
All masters must have a some service,    
but if you don't need this - the ALL masters should NOT have any services.    
Otherwise, in the logic of order processing errors may occur.    
Logic:   
if each master have at least one service - check appointments times for current master;    
if masters not services - we believe that every master performs any service    
and check appointments times for all masters    
and if at least one master is free - client can sign up.    

When masters created also user created with status "user"    
with "name" - number of phone (+01112223344)    
with email - number of phone@com.com (+01112223344@com.com)    
with password - "password"     


### SIGN UP
### Settings:
### By moder of organisation:
`endtime = "17:00"`    
Время, после которого даты начинаются с завтрашней (те запись на сегодня уже недоступна)    
The time after which the dates start from tomorrow (those records are no longer available for today)    

`lehgth_cal = 14`    
Количество отображаемых дней для записи    
The number of days displayed for an appointment    

`tz = "Europe/Simferopol"`    
Часовой пояс    
Timezone    

`period = 60`    
Интервал времен для записи (09:00, 10:00, 11:00, ...),    
мб любой, преобразуется кратно 10,, то есть 7 мин -> 10 мин, 23 мин -> 30 мин и тп    
кроме промежутков > 10, но < 15 - преобразуется в 15 минутный промежуток    
Time interval for an appointment, can be any, converted multiple of 10    
but if 10 < $period < 15 then = 15     

`lunch = ["12:00", 60]`     
Массив c двумя значениями: время начала HH:mm и длительность обеденного перерыва в минутах    
An array with two values: the start time HH:mm and the duration of the lunch break in minutes    

`worktime = ['09:00', '19:00']`    
Рабочее время $worktime[0] - начало, $worktime[1] - конец    
Working time $worktime[0] - start, $worktime[1] - end    

`org_weekend = {'Сб': '14:00', 'Вс': ''}`    
Постоянно планируемые в организации выходные, ключ - название дня,    
значение - пустое, если целый день выходной,    
или время начала отдыха в 24часовом формате HH:mm    
Weekends that are constantly planned in the organization, the key is the name of the day,    
the value is empty if the whole day is off,    
or the start time of the rest in the 24-hour format HH:mm    

`holiday = ['2023-02-23', '2023-03-08', '2023-03-17']` - праздничные дни хоть на 10 лет вперед    

### DATA related to a specific MASTER:    
`rest_day_time = {'2023-03-15': [], '2023-03-13': ['16:00', '17:00', '18:00'],'2023-03-14': ['10:00', '11:00', '14:00'] }`    
Запланированные выходные дни и часы мастера,    
получены из рабочего графика мастера, если массив значений пуст - выходной целый день.    
Значение равно началу времени отгула, длительность не указывается и будет равна $period,    
те, если период = 60 минут, а отсутствовать мастер будет 2 часа после 17:00    
запись такая `rest_day_time = {'дата YYYY-mm-dd': ['17:00', '18:00']}`    

The scheduled days off and the master's hours,    
are obtained from the master's work schedule, if the array of values is empty - the whole day off.    
The value is equal to the beginning of the time off, the duration is not specified and will be equal to $period,    
those if the period = 60 minutes, and the master will be absent 2 hours after 17:00      
the entry is `rest_day_time = {'date YYYY-mm-dd' => ['17:00', '18:00']}`    

`exist_app_date_time_arr`    
Объект предыдущих записей к мастеру    
в формате `{'date': {time: 'duration', ...}, ...)`,     
где date - datetime (часы и минуты 00:00)    
а time - datetime (с ненулевым временем): 'duration' - длительность услуги в минутах,     
длительность можно не указывать (null or ''), тогда она считается равной $period     

Array of previous entries to the master     
in the array format `{'date': {time: 'duration', ...}, ...)`      

Screenshots:   
Admin home if user status is admin:    
![Alt text](screenshots/000_admin_page.jpg "Optional title")
Admin home if user status is moder:   
![Alt text](screenshots/001_admin_moder_page.jpg "Optional title")
Admin home if user status is user (usually this user is master):   
![Alt text](screenshots/002_admin_user_master_page.jpg "Optional title")     
Edit users:   
![Alt text](screenshots/003_admin_user_change.jpg "Optional title")
![Alt text](screenshots/004_admin_user_delete.jpg "Optional title")    
Sign up settings:    
![Alt text](screenshots/005_admin_signup_settings.jpg "Optional title")      
Logs:    
![Alt text](screenshots/006_admin_logs.jpg "Optional title")    
Contacts editing:   
![Alt text](screenshots/010_admin_moder_contacts.jpg "Optional title")
![Alt text](screenshots/011_admin_moder_contacts_list.jpg "Optional title")
![Alt text](screenshots/012_admin_moder_contacts_create.jpg "Optional title")
![Alt text](screenshots/013_admin_moder_contacts_delete.jpg "Optional title")
![Alt text](screenshots/014_admin_moder_contacts_edit.jpg "Optional title")
![Alt text](screenshots/015_admin_moder_contacts_edit.jpg "Optional title")     
Pages creating and editing:     
![Alt text](screenshots/020_admin_moder_pages_create_info.jpg "Optional title")
![Alt text](screenshots/021_admin_moder_pages_create.jpg "Optional title")
![Alt text](screenshots/022_admin_moder_pages_edit_list.jpg "Optional title")
![Alt text](screenshots/023_admin_moder_pages_edit_edit.jpg "Optional title")     
Service page creating and editing:    
![Alt text](screenshots/024_admin_moder_services_pages_edit_list.jpg "Optional title")
![Alt text](screenshots/025_admin_moder_services_pages_edit_edit.jpg "Optional title")
![Alt text](screenshots/026_admin_moder_services_edit_page_choice.jpg "Optional title")
![Alt text](screenshots/027_admin_moder_services_edit_category_add.jpg "Optional title")
![Alt text](screenshots/028_admin_moder_services_edit_category_delete.jpg "Optional title")
![Alt text](screenshots/028_admin_moder_services_edit_services_add.jpg "Optional title")
![Alt text](screenshots/029_admin_moder_services_edit_services_delete.jpg "Optional title")    
Price editing:   
![Alt text](screenshots/030_admin_moder_prices_edit_page_choice.jpg "Optional title")
![Alt text](screenshots/031_admin_moder_prices_edit.jpg "Optional title")    
Abouts page editing:    
![Alt text](screenshots/040_admin_moder_abouts_articles_add.jpg "Optional title")
![Alt text](screenshots/041_admin_moder_abouts_articles_delete.jpg "Optional title")
![Alt text](screenshots/042_admin_moder_abouts_articles_postdelete.jpg "Optional title")
![Alt text](screenshots/043_admin_moder_abouts_articles_edit.jpg "Optional title")     
Gallery editing:    
![Alt text](screenshots/050_admin_moder_gallery_edit.jpg "Optional title")
![Alt text](screenshots/051_admin_moder_gallery_add.jpg "Optional title")
![Alt text](screenshots/052_admin_moder_gallery_remove.jpg "Optional title")
![Alt text](screenshots/053_admin_moder_gallery_albumlink_edit.jpg "Optional title")     
Map editing:      
![Alt text](screenshots/060_admin_moder_map_edit.jpg "Optional title")     
Masters editing:    
![Alt text](screenshots/070_admin_moder_masters_edit_list.jpg "Optional title")
![Alt text](screenshots/071_admin_moder_masters_edit_edit.jpg "Optional title")
![Alt text](screenshots/072_admin_moder_masters_create.jpg "Optional title")
![Alt text](screenshots/073_admin_moder_masters_schedule_choice_master.jpg "Optional title")
![Alt text](screenshots/074_admin_moder_masters_schedule_edit.jpg "Optional title")     
Signup list and editing:     
![Alt text](screenshots/080_admin_moder_signup_by_date.jpg "Optional title")
![Alt text](screenshots/081_admin_moder_signup_by_master.jpg "Optional title")
![Alt text](screenshots/082_admin_moder_signup_by_client_choice.jpg "Optional title")
![Alt text](screenshots/083_admin_moder_signup_by_client.jpg "Optional title")
![Alt text](screenshots/084_admin_moder_signup_by_client_edit.jpg "Optional title")
![Alt text](screenshots/085_admin_moder_signup_edit_time.jpg "Optional title")
![Alt text](screenshots/086_admin_moder_signup_edit_master.jpg "Optional title")
![Alt text](screenshots/087_admin_moder_signup_past.jpg "Optional title")
![Alt text](screenshots/088_admin_moder_signup_future.jpg "Optional title")       
Callbacks:    
![Alt text](screenshots/090_admin_moder_callback_need.jpg "Optional title")
![Alt text](screenshots/091_admin_moder_callback_complete.jpg "Optional title")     
Admin home for master:   
![Alt text](screenshots/100_admin_master_page.jpg "Optional title")
![Alt text](screenshots/101_admin_master_signup.jpg "Optional title")
Client pages:     
![Alt text](screenshots/200_client_home_page.jpg "Optional title")
![Alt text](screenshots/201_client_callback.jpg "Optional title")
![Alt text](screenshots/202_client_signup_start.jpg "Optional title")
![Alt text](screenshots/203_client_signup_service.jpg "Optional title")
![Alt text](screenshots/204_client_signup_master.jpg "Optional title")
![Alt text](screenshots/205_client_signup_time.jpg "Optional title")
![Alt text](screenshots/206_client_signup_check_free.jpg "Optional title")
![Alt text](screenshots/208_client_signup_end.jpg "Optional title")
![Alt text](screenshots/209_client_service_page_list.jpg "Optional title")
![Alt text](screenshots/210_client_service_category_page.jpg "Optional title")
![Alt text](screenshots/211_client_service_page.jpg "Optional title")
![Alt text](screenshots/212_client_price_page.jpg "Optional title")
![Alt text](screenshots/213_client_gallery_page.jpg "Optional title")
![Alt text](screenshots/214_client_gallery_page.jpg "Optional title")
![Alt text](screenshots/215_client_about_page.jpg "Optional title")
![Alt text](screenshots/216_client_map_page.jpg "Optional title")
