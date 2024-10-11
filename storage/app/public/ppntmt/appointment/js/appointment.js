//////////////////////////////////////
// result html out and listener reset and submit
function demo_app_if_no_php(calendar) {
  let data_obj = {};
  data_obj.lehgth_cal = 14;
  data_obj.endtime = "17:00";
  data_obj.period = 60;
  data_obj.worktime = ['09:00', '19:00'];
  data_obj.lunch = ["12:00", 60];
  data_obj.org_weekend = {'Сб': '14:00', 'Вс': ''};
  data_obj.rest_day_time = {'2023-06-21': [], '2023-06-26': [], '2023-06-23': ['16:00', '17:00', '18:00'],'2023-06-28': ['10:00', '11:00', '14:00'] };
  data_obj.holiday = ['2023-02-23', '2023-03-08', '2023-05-01', '2023-06-12', '2023-06-30'];
  data_obj.exist_app_date_time_arr = {
    '2023-03-17': {'11:00': '', '13:00': '', '14:30': null},
    '2023-03-25': {'13:00': '30', '13:30': '30', '15:00': 40},
    '2023-03-21': {'09:00': '140'},
    '2023-03-23': {'09:00': '40', '09:40': '30', '10:10': '60'}};
  data_obj.serv_duration = '120';

  all_in_one(calendar, data_obj);
}
//for form in page
function appointment(calendar, url, service_id, master_id = '', token = '') {

  async function data_from_db(url, enter_data = '') { // or data = {}
    const myHeaders = {
      //'Content-Type': 'application/json'
      'Content-Type': 'application/x-www-form-urlencoded',
      "X-CSRF-TOKEN": token
    };

    const myInit = {
      method: 'POST',
      mode: 'same-origin', // no-cors, *cors, same-origin
      cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
      credentials: 'same-origin', // include, *same-origin, omit
      headers: myHeaders,
      redirect: 'follow', // manual, *follow, error
      referrerPolicy: 'no-referrer', // no-referrer, *client
      body: enter_data
      // JSON.stringify(data) // body data type must match "Content-Type" header
    };
    const myRequest = new Request(url, myInit);
    const response = await fetch(myRequest);
    const contentType = response.headers.get('content-type');
    //const mytoken = response.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
      throw new TypeError("Data from server is not JSON!");
    }

    return await response.json();
  }

  data_from_db(url, "master_id=" + master_id + "&service_id=" + service_id)
    .then(promise => promise)
    .then(data_obj => {

      data_obj.lehgth_cal = (data_obj.lehgth_cal !== null) ? data_obj.lehgth_cal : 14;
      data_obj.endtime = (data_obj.endtime !== null) ? data_obj.endtime : "17:00";
      data_obj.period = (data_obj.period !== null) ? data_obj.period : 60;
      data_obj.worktime = (data_obj.worktime !== null) ? data_obj.worktime : ['09:00', '19:00'];
      data_obj.lunch = (data_obj.lunch !== null) ? data_obj.lunch : ["12:00", 60];
      data_obj.org_weekend = (data_obj.org_weekend !== null) ? data_obj.org_weekend : { 'Сб': '14:00', 'Вс': '' };
      data_obj.rest_day_time = (data_obj.rest_day_time !== null) ? data_obj.rest_day_time : { '2023-06-21': [], '2023-06-26': [], '2023-06-23': ['16:00', '17:00', '18:00'], '2023-06-28': ['10:00', '11:00', '14:00'] };
      data_obj.holiday = (data_obj.holiday !== null) ? data_obj.holiday : ['2023-02-23', '2023-03-08', '2023-05-01', '2023-06-12', '2023-06-30'];
      data_obj.exist_app_date_time_arr = (data_obj.exist_app_date_time_arr !== null) ? data_obj.exist_app_date_time_arr : {
        '2023-03-17': { '11:00': '', '13:00': '', '14:30': null },
        '2023-03-25': { '13:00': '30', '13:30': '30', '15:00': 40 },
        '2023-03-21': { '09:00': '140' },
        '2023-03-23': { '09:00': '40', '09:40': '30', '10:10': '60' }
      };
      data_obj.serv_duration = (data_obj.serv_duration !== null) ? data_obj.serv_duration : '120';

      all_in_one(calendar, data_obj);

      let mdt = document.querySelector('.master_datetime');
      if (!!mdt) {
        mdt.addEventListener('click', function (dt_el) {
          if (dt_el.target.disabled != "disabled" && dt_el.target.name == 'time') {
            //location.href = "#buttons_div";
            let butdiv = document.getElementById("buttons_div");
            let butnext = document.getElementById("button_next");
            if (!!butdiv && !!butnext) {
              butdiv.scrollIntoView();
              butnext.focus();
            }
          }
        });
      }
    })
    .catch(function (err) {
      console.log("Fetch Error :-S", err);
    });
}

function all_in_one(calendar, object_with_data_from_db) {
  data_obj = object_with_data_from_db;
  //var action = "path/to/appointment.php"
  var action = ""
  var method = "post"
  var form_id = "zapis_usluga_form"
  var formm = document.querySelector('#' + form_id)

  //set the pages element for calendar
  var div_id = "time_choice"
  var div_short = document.querySelector('#' + div_id)
  var div_sched = document.querySelector('#' + div_id)
  var div_month = document.querySelector('#' + div_id)

  var locale = 'ru-RU' // for time localisation eg en-EN us-US
  var long = 'short' // for week day name eg 'long'

  const mark = "disabled"

  /*
  Алгоритм:
  Условия: все временные метки для дней с нулевым временем (00:00:00)
  1. Сформировать объект дат с учетом выходных из org_weekend (только дни с пустыми временами начала выходных)
  date = { date(datetime): mark(dis or check) }
  2. Сформировать объект всех дат-времен с начала рабочего дня с указанным периодом до конца рабочего дня без времени обеда
  dt = {
    date0(datetime): { time0(datetime): "", time1(datetime): "", ..., timen(datetime): "" },
    ...
    daten(datetime): { time0(datetime): "", time1(datetime): "", ..., timen(datetime): "" },
  }
  3. Пометить времена в объекте дат-времен как отключенные, если они есть в объектах rest_day_time (часы отдыха),
  exist_app_dt_arr (записи на прием) и org_weekend (даты со временем начала выходных)
  */

  var months_full_name = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
  var months = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];
  // Дни недели с понедельника
  var days_of_week = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
  var dw = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
  /*
  const year = element.getFullYear();
  const month = element.getMonth();
  const day = element.getDate();
  const Yearmonthday  = year + "-" + pad((month+1)) + "-" + pad(day);
  */
  function pad(n) {
    return n < 10 ? '0' + n : n;
  }

  Date.prototype.addHours = function (h) {
    this.setTime(this.getTime() + (h * 60 * 60 * 1000));
    return this;
  }
  /**
   * var time_not_allowed - промежуток времени, в который нельзя записаться
   * на сегодня после текущего времени
   * например сейчас 13.00 - если time_not_allowed = 1: 14.00 будет занято,
   * записаться можно на 15.00
   * Нужен, чтобы клиенты не записывались в последний момент
   */
  let time_not_allowed = 1;

  /**
  * Adds time to a date. Modelled after MySQL DATE_ADD function.
  * Example: dateAdd(new Date(), 'minute', 30)  //returns 30 minutes from now.
  * https://stackoverflow.com/a/1214753/18511
  *
  * @param date  Date to start with
  * @param interval  One of: year, quarter, month, week, day, hour, minute, second
  * @param units  Number of units of the given interval to add.
  */
  function dateAdd(date, interval, units) {
    if (!(date instanceof Date))
      return undefined;
    var ret = new Date(date); //don't change original date
    var checkRollover = function () { if (ret.getDate() != date.getDate()) ret.setDate(0); };
    switch (String(interval).toLowerCase()) {
      case 'year': ret.setFullYear(ret.getFullYear() + units); checkRollover(); break;
      case 'quarter': ret.setMonth(ret.getMonth() + 3 * units); checkRollover(); break;
      case 'month': ret.setMonth(ret.getMonth() + units); checkRollover(); break;
      case 'week': ret.setDate(ret.getDate() + 7 * units); break;
      case 'day': ret.setDate(ret.getDate() + units); break;
      case 'hour': ret.setTime(ret.getTime() + units * 3600000); break;
      case 'minute': ret.setTime(ret.getTime() + units * 60000); break;
      case 'second': ret.setTime(ret.getTime() + units * 1000); break;
      default: ret = undefined; break;
    }
    return ret;
  }

  //function for getting all dates for processing (all times is 00:00:00)
  function getDaysArray(start, end) {
    for (var arr = [], dt = new Date(start); dt <= new Date(end); dt.setDate(dt.getDate() + 1)) {
      dt.setHours(0, 0, 0, 0)
      arr.push(new Date(dt));
    }
    return arr;
  };

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  function work_time_start_dt(data) {
    const work_time_start = data_obj.worktime[0].split(':')
    const wksdt = new Date(data).setHours(work_time_start[0], work_time_start[1])
    return wksdt
  }
  function work_time_end_dt(data) {
    const work_time_end = data_obj.worktime[1].split(':')
    const wkedt = new Date(data).setHours(work_time_end[0], work_time_end[1])
    return wkedt
  }
  //get name of day of week
  function getDayName(dateStr, locale, long) {
    if (!dateStr) dateStr = new Date()
    if (!long) long = "long"
    if (!locale) locale = "ru-RU"
    var date = new Date(dateStr);
    return date.toLocaleDateString(locale, { weekday: long });
  }

  function round_period() {
    if (data_obj.period < 10) {
      rp = data_obj.period * 60
    } else {
      rp = (data_obj.period > 5 && data_obj.period < 16) ? 15 : Math.ceil(Number(data_obj.period / 10)) * 10
    }
    return rp
  }
  //let period = round_period()

  function lunch(datetime_string) {
    const dt = (datetime_string) ? new Date(datetime_string) : new Date();
    let lunch_array = []
    const lunch_arr = data_obj.lunch[0].split(':')
    const lunch_hour = lunch_arr[0]
    const lunch_min = lunch_arr[1]
    const lunch_dur = data_obj.lunch[1]
    const lunchstart_dt = new Date(new Date(datetime_string).setHours(lunch_hour, lunch_min, 0, 0));
    const lunchend_dt = dateAdd(lunchstart_dt, 'minute', lunch_dur)
    return lunch_array = [lunchstart_dt, lunchend_dt]
  }

  //get all dates for processing
  function all_dates() {
    let start = Date.now()
    let end = (new Date()).setDate((new Date()).getDate() + (data_obj.lehgth_cal - 1))
    let res = getDaysArray(start, end)

    let now = new Date()
    let tm = data_obj.endtime.split(":")
    let endnow = new Date((new Date(now)).setHours(tm[0], tm[1]))
    //IF CURRENT TIME MORE THEN data_obj.endtime - DEL FIRST DATE (TODAY) AND PUSH MORE ONE DATE INTO DATEARRAY
    if (now.getTime() > endnow.getTime()) {
      //get last elem
      let last_day = res.at(-1)
      //add one day
      let ld = new Date(last_day)
      let add_day = ld.setDate(ld.getDate() + 1)
      res.push(new Date(add_day))
      res.shift()
    }
    return res;
  }
  //let dateArray = all_dates()

  // create holiday and rest days arrays
  function holidays() {
    if (data_obj.holiday.constructor.name == "Array") {
      //var holiday = data_obj.holiday.map(item => new Date(new Date(item).setHours(0, 0, 0, 0)));
      var holiday = data_obj.holiday.map(item => new Date(item).setHours(0, 0, 0, 0));
    }
    return holiday
  }
  //let holiday = holidays()
  //console.log(holiday)

  function rest_days() {
    var restdays = []
    for (const key in data_obj.rest_day_time) {
      if (Object.hasOwnProperty.call(data_obj.rest_day_time, key)) {
        const dt = new Date(key)
        dt.setHours(0, 0, 0, 0)
        const element = data_obj.rest_day_time[key];
        if (element.length <= 0) {
          //restdays.push(key)
          date = new Date(key).setHours(0, 0, 0, 0)
          restdays.push(date)
        }
      }
    }
    return restdays
  }
  //let restdays = rest_days()
  //console.log(restdays)

  // ALL DAYS MARKED - disable if weekend, checked if first work day
  function marked_dates() {
    let holiday = holidays()
    let restdays = rest_days()
    let dateArray = all_dates()
    let days = {}
    for (let index = 0; index < dateArray.length; index++) {
      let element = dateArray[index];
      let el = '';
      const year = element.getFullYear();
      const month = element.getMonth();
      const day = element.getDate();
      const Yearmonthday = year + "-" + pad((month + 1)) + "-" + pad(day);

      //if date is holiday - mark it
      //const hol = holiday.find(date => date.toDateString() === element.toDateString());
      const hol = holiday.includes(element.getTime());
      if (hol) {
        days[element] = "disabled"
        //div_cont.innerHTML = div_cont.innerHTML + dateArray[index] +'\n<br>';
        continue
      }

      //if name of day of week existed in data_obj.org_weekend - mark it
      const name_of_day = getDayName(element, locale, long)
      const cnd = capitalizeFirstLetter(name_of_day)
      if (((name_of_day in data_obj.org_weekend)
        && (data_obj.org_weekend[name_of_day] === '' || data_obj.org_weekend[name_of_day] === null))
        || ((cnd in data_obj.org_weekend) && (data_obj.org_weekend[cnd] === '' || data_obj.org_weekend[cnd] === null))
      ) {
        days[element] = "disabled"
        continue
      }

      const rest = (restdays.includes(element.getTime()))
      if (rest) {
        days[element] = "disabled"
        continue
      }
      // if el not isset
      days[element] = ""
    }
    //checked first work day
    for (const key in days) {
      if (Object.hasOwnProperty.call(days, key)) {
        const element = days[key];
        if (element === "") {
          days[key] = 'checked'
          break
        }
      }
    }
    //console.log(days)
    return days
  }
  //var marked_date_obj = marked_dates()
  //console.log(marked_date_obj)

  // times for date
  function times(datestring) {
    const period = round_period()
    const dt = (datestring) ? new Date(datestring) : new Date();
    const year = dt.getFullYear();
    const month = dt.getMonth();
    const day = dt.getDate();

    let lunchh = lunch(datestring)
    lunchstart_dt = lunchh[0]
    lunchend_dt = lunchh[1]

    let times = {}

    let startt = data_obj.worktime[0].split(':')
    let endd = data_obj.worktime[1].split(':')
    const start = new Date(year, month, day, startt[0], startt[1], 0, 0);
    const end = new Date(year, month, day, endd[0], endd[1], 0, 0);
    for (let index = start; index < end; index = dateAdd(index, 'minute', period)) {
      if (index < lunchstart_dt || index >= lunchend_dt) {
        times[index] = ''
        //console.log(pad(hours)+':'+pad(min))
      }
    }
    if (!(lunchstart_dt in times)) {
      times[lunchstart_dt] = 'disabled'
    }
    if (!(lunchend_dt in times)) {
      times[lunchend_dt] = ''
    }
    return times;
  }
  //let times_arr = times('2023-03-25')
  //console.log(times_arr)

  // ALL DATE_TIMES
  function date_times() {
    let marked_date_obj = marked_dates()
    let dt = {}
    for (const date in marked_date_obj) {
      if (Object.hasOwnProperty.call(marked_date_obj, date)) {
        const mark_d = marked_date_obj[date];
        if (mark_d === '' || mark_d === 'checked') {
          dt[date] = times(date)
          //console.log(dt[date])
        }
      }
    }
    return dt
  }
  //let dt_obj = date_times()
  //console.log(dt_obj)

  function rest_dt() {
    let period = round_period()
    // create object with rest times
    let rest_times = {}
    for (const date in data_obj.rest_day_time) {
      if (Object.hasOwnProperty.call(data_obj.rest_day_time, date)) {
        const element = data_obj.rest_day_time[date];
        if (element.length > 0) {
          rest_times[date] = {}
          for (let i = 0; i < element.length; i++) {
            const el = element[i];
            const h_m_t = el.split(':')
            const h = h_m_t[0]
            const m = h_m_t[1]
            const start = new Date(new Date(date).setHours(h, m, 0, 0))
            const end = new Date(dateAdd(new Date(start), 'minute', period).getTime())
            rest_times[date][start] = 'disabled'
            if (end < work_time_end_dt(date)) {
              if (!(end in rest_times[date])) {
                rest_times[date][end] = ''
              }
            }
          }
        }
      }
    }
    //console.log(rest_times)
    let dt = date_times()
    for (const date in dt) {
      if (Object.hasOwnProperty.call(dt, date)) {
        const times = dt[date];
        for (const data in rest_times) {
          if (Object.hasOwnProperty.call(rest_times, data)) {

            const rest = rest_times[data];
            for (const rest_t in rest) {
              if (Object.hasOwnProperty.call(rest, rest_t)) {
                if (rest_t in times && times[rest_t] === '') {
                  dt[date][rest_t] = rest[rest_t]
                } else if (date === data && !(rest_t in times)) {
                  dt[date][rest_t] = rest[rest_t]
                }
              }
            }
          }
        }
      }
    }
    //console.log(dt)
    return dt
  }
  //let rest = rest_dt()
  //console.log(rest)

  function app_times() {
    let period = round_period()
    //create appointment object from data_obj.exist_app_date_time_arr
    let date_time = rest_dt()
    let exist_app_date_time_obj = {}
    let start_end = {}
    for (const data in data_obj.exist_app_date_time_arr) {
      if (Object.hasOwnProperty.call(data_obj.exist_app_date_time_arr, data)) {
        const dt = new Date(new Date(data).setHours(0, 0, 0, 0))
        exist_app_date_time_obj[dt] = {}
        start_end[dt] = []

        const times = data_obj.exist_app_date_time_arr[data];
        //console.log(element)
        for (const time in times) {
          if (Object.hasOwnProperty.call(times, time)) {
            let app_end = ''
            let hour = time.split(':')
            const app_start = new Date(new Date(data).setHours(hour[0], hour[1]))
            const dur = times[time];
            if (dur) {
              //if length of service > 5 then minutes, else hours
              //если длительность услуги меньше 5  - значит обозначено в часах
              const interval = (dur > 5) ? 'minute' : 'hour';
              const serv_dur = (dur > 5) ? dur : Math.ceil(Number(dur * 60 / 10)) * 10
              app_end = dateAdd(app_start, interval, serv_dur)
            } else {
              app_end = dateAdd(app_start, 'minute', period);
            }
            exist_app_date_time_obj[dt][app_start] = 'disabled'
            start_end[dt].push([app_start, app_end])

            if (app_end < work_time_end_dt(dt)) {
              if (!(app_end in exist_app_date_time_obj[dt])) {
                exist_app_date_time_obj[dt][app_end] = ''
              }
            }
          }
        }
      }
    }
    //console.log(exist_app_date_time_obj)
    //console.log(start_end)

    // объединим объект date_time (с отмеченными выходными часами) и объект с временами начала и окончания записей на услуги
    // те, если такого времени нет - допишем его
    for (const app_date in exist_app_date_time_obj) {
      if (Object.hasOwnProperty.call(exist_app_date_time_obj, app_date)) {
        const app_times = exist_app_date_time_obj[app_date];
        if (app_date in date_time) {
          for (const app_time in app_times) {
            if (Object.hasOwnProperty.call(app_times, app_time)) {
              let app_mark = app_times[app_time]
              if (app_time in date_time[app_date] && date_time[app_date][app_time] != 'disabled') {
                date_time[app_date][app_time] = app_mark
              }
              if (!(app_time in date_time[app_date])) {
                date_time[app_date][app_time] = app_mark
              }
            }
          }
        }
      }
    }

    //пометим времена услуг
    for (const se_date in start_end) {
      if (Object.hasOwnProperty.call(start_end, se_date)) {
        const se_arr = start_end[se_date];
        if (se_date in date_time) {
          for (let i = 0; i < se_arr.length; i++) {
            const se = se_arr[i];
            for (const dt_time in date_time[se_date]) {
              if (Object.hasOwnProperty.call(date_time[se_date], dt_time)) {
                const dt_mark = date_time[se_date][dt_time];
                if ((se[0].getTime() < new Date(dt_time).getTime() || se[0].getTime() < new Date(dt_time).getTime()) && se[1].getTime() > new Date(dt_time).getTime() && (dt_mark === '' || dt_mark === null)) {
                  date_time[se_date][dt_time] = 'disabled'
                }
              }
            }
          }
        }
      }
    }
    //console.log(date_time)
    return date_time
  }
  //let exist_app = app_times()
  //console.log(exist_app)

  //console.log(marked_date_obj)
  function weekend_times() {
    // use appa_times as date_times without rest days
    let date_time = app_times()
    let h_m = ''

    for (const date in date_time) {
      const dt_times = date_time[date];
      const name_of_day = getDayName(new Date(date), locale, long)
      const cnd = capitalizeFirstLetter(name_of_day)

      let z1 = name_of_day in data_obj.org_weekend
      let z2 = cnd in data_obj.org_weekend
      if (z1 && (data_obj.org_weekend[name_of_day] !== '' || data_obj.org_weekend[name_of_day] !== null)) {
        h_m = data_obj.org_weekend[name_of_day].split(':')
      } else if (z2 && (data_obj.org_weekend[cnd] !== '' || data_obj.org_weekend[cnd] !== null)) {
        h_m = data_obj.org_weekend[cnd].split(':')
      }
      if ((z1 || z2) && h_m !== '') {
        const hour = h_m[0]
        const min = h_m[1]
        const week_start = new Date(new Date(date).setHours(hour, min, 0, 0))
        //console.log(week_start)
        for (const time in dt_times) {
          if (Object.hasOwnProperty.call(dt_times, time)) {
            if (week_start.getTime() <= new Date(time).getTime()) {
              date_time[date][time] = mark;
            }
          }
        }
      }
    }
    //console.log(date_time)
    return date_time
  }
  //let weekend_date_times = weekend_times()

  function sort_date_time_arr() {
    let date_time = weekend_times()
    for (const date in date_time) {
      if (Object.hasOwnProperty.call(date_time, date)) {
        const times = date_time[date];

        const sort_times = Object.keys(times).sort().reduce(
          (obj, key) => {
            obj[key] = times[key];
            return obj;
          },
          {}
        );
        date_time[date] = sort_times
      }
    }
    return date_time
  }
  //let result_date_time = sort_date_time_arr()
  //console.log(result_date_time)

  function modal_alert(message_string) {
    var newDiv = document.createElement('div');
    newDiv.classList.add('modal')
    newDiv.id = "alert"
    newDiv.innerHTML = '<div><p>' + message_string + '</p><button id="alert_ok">OK</button></div>';
    // Добавляем только что созданный элемент в дерево DOM
    if (!!div_month) {
      my_div = div_month
    }
    if (!!div_short) {
      my_div = div_short
    }
    //document.body.insertBefore(newDiv, my_div);
    document.querySelector('#time_choice').parentNode.insertBefore(newDiv, my_div);
    // setup body no scroll
    document.body.style.overflow = 'hidden';

    let but = document.getElementById('alert_ok')
    but.focus()
    but.addEventListener('click', function (ev) {
      newDiv.remove()
      // setup body scroll
      document.body.style.overflow = 'visible';
    })
  }

  ///////////////////////////////////////////////
  //CHECK IF SERV DURATION < time interval between appointment times
  function serv_duration() {
    let message0 = ' Недостаточно времени для оказания услуги до конца рабочего дня.\n<br /> Пожалуйста, выберите другое время.';
    let message1 = ' Недостаточно свободного времени для оказания услуги.\n<br /> Пожалуйста, выберите другое время.';

    const dur = data_obj.serv_duration
    if (!!div_short || !!div_month) {
      let t_div = document.querySelectorAll('.master_times')
      if (!!t_div) {
        t_div.forEach(element => element.addEventListener("change", function (ev) {
          //dt = ev.target.id
          let date_inp_chek = document.querySelector('input[type="radio"][name="date"]:checked');
          let time_inp_chek = document.querySelector('input[type="radio"][name="time"]:checked');
          if (!!time_inp_chek && date_inp_chek) {
            let ttime = time_inp_chek.value
            let dtime = date_inp_chek.value
            const serv_dt_start = new Date().setTime(ttime);
            const serv_dt_end = dateAdd(new Date(new Date().setTime(ttime)), 'minute', dur).getTime();
            const date = new Date(new Date(serv_dt_start).setHours(0, 0, 0, 0))
            let end_work_time_dt = work_time_end_dt(new Date().setTime(dtime));
            //next time
            const dt_arr = sort_date_time_arr()
            if (date in dt_arr) {
              let times = Object.entries(dt_arr[date])
              //find next value with disabled and compare with serv_end
              //if less - ok, if more - not ok: shoose other time
              for (let index = 0; index < times.length; index++) {
                // укажем нужный элемент массива дат-времен
                const elem = times[index];
                const elem_t = new Date(elem[0]).getTime()

                if (elem_t === serv_dt_start) {
                  // если след элем == последнему элементу массива - проверим,
                  // что длительность услуги не больше чем конец раб времени
                  let ind = index + 1;
                  if ((ind) === times.length) {
                    if (serv_dt_end > end_work_time_dt) {
                      modal_alert(message0);
                      //alert('Недостаточно времени для оказания услуги до конца рабочего дня.\n Пожалуйста, выберите другое время.');
                      time_inp_chek.checked = false;
                      break;
                    }
                  } else if ((ind) < times.length) {
                    // найдем первый элемент массива после текущего, в котором есть disabled
                    // и проверим, что длительность услуги укладывается в этот интревал
                    for (ind; ind < times.length; ind++) {
                      let next = times[ind];
                      let dis = next[1]
                      let next_time_dt = new Date(next[0]);
                      if (dis) {
                        if (serv_dt_end > next_time_dt) {
                          modal_alert(message1)
                          //alert('Недостаточно свободного времени для оказания услуги.\n Пожалуйста, выберите другое время.');
                          time_inp_chek.checked = false;
                          break;
                        }
                      }
                    }
                  }
                  break;
                }
              }
            }
          }
        }))
      }
    }
  }

  function serv_dur_month() {
    serv_duration()
    let d_div = document.querySelector('#tableId > tbody')
    if (!!d_div) {
      d_div.addEventListener('click', function (ev) {
        serv_duration()
      })
    }
  }

  ////////////////////////////////////////
  // month calendar
  var Month = function (divId, div_id_for_times_out) {
    //Сохраняем идентификатор div
    this.divId = divId;
    this.div_id_for_times_out = div_id_for_times_out;
    // Дни недели с понедельника
    this.DaysOfWeek = days_of_week
    // Месяцы начиная с января
    this.Months = months_full_name;
    //Устанавливаем текущий месяц, год
    var d = new Date();
    this.currMonth = d.getMonth();
    this.currYear = d.getFullYear();
    this.currDay = d.getDate();
  };
  // Переход к следующему месяцу
  Month.prototype.nextMonth = function () {
    if (this.currMonth == 11) {
      this.currMonth = 0;
      this.currYear = this.currYear + 1;
    }
    else {
      this.currMonth = this.currMonth + 1;
    }
    this.showcurr();
  };
  // Переход к предыдущему месяцу
  Month.prototype.previousMonth = function () {
    if (this.currMonth == 0) {
      this.currMonth = 11;
      this.currYear = this.currYear - 1;
    }
    else {
      this.currMonth = this.currMonth - 1;
    }
    this.showcurr();
  };
  // Показать текущий месяц
  Month.prototype.showcurr = function () {
    this.showMonth(this.currYear, this.currMonth);
  };
  // Показать месяц (год, месяц)
  Month.prototype.showMonth = function (y, m) {
    let marked_date_obj = marked_dates()
    var d = new Date()
      // Первый день недели в выбранном месяце
      , firstDayOfMonth = new Date(y, m, 7).getDay()
      // Последний день выбранного месяца
      , lastDateOfMonth = new Date(y, m + 1, 0).getDate()
      // Последний день предыдущего месяца
      , lastDayOfLastMonth = m == 0 ? new Date(y - 1, 11, 0).getDate() : new Date(y, m, 0).getDate();
    var html = '<table id="tableId" class="table_month_cal">';
    // Запись выбранного месяца и года
    html += '<thead><tr>';
    html += '<td colspan="7" class="td_month_cal thead_td_month_cal">' + this.Months[m] + ' ' + y + '</td>';
    html += '</tr></thead>';
    // заголовок дней недели
    html += '<tr class="days">';
    for (var i = 0; i < this.DaysOfWeek.length; i++) {
      html += '<td class="td_month_cal">' + this.DaysOfWeek[i] + '</td>';
    }
    html += '</tr>';

    // Записываем дни
    var i = 1;
    do {
      var dow = new Date(y, m, i).getDay();
      // Начать новую строку в понедельник
      if (dow == 1) {
        html += '<tr>';
      }
      // Если первый день недели не понедельник показать последние дни предыдущего месяца
      else if (i == 1) {
        html += '<tr>';
        var k = lastDayOfLastMonth - firstDayOfMonth + 1;
        for (var j = 0; j < firstDayOfMonth; j++) {
          html += '<td class="not-current td_month_cal">' + k + '</td>';
          k++;
        }
      }
      // Записываем текущий день
      var chk = new Date();
      var chkY = chk.getFullYear();
      var chkM = chk.getMonth();
      let date = new Date(this.currYear, this.currMonth, i, 0, 0, 0, 0)
      const id = date.getTime()
      if (chkY == this.currYear && chkM == this.currMonth && (i < this.currDay || (i > this.currDay + data_obj.lehgth_cal))) {
        html += '<td class="not-current td_month_cal day" id="' + id + '">' + i + '</td>';
      } else if (chkY == this.currYear && chkM == this.currMonth && (i > this.currDay || (i < this.currDay + data_obj.lehgth_cal)) && date in marked_date_obj && marked_date_obj[date] !== 'disabled') {
        if (marked_date_obj[date] === 'checked') {
          html += '<td class="today td_month_cal day" id="d_' + id + '">' + i + '</td>';
        } else {
          html += '<td class="normal td_month_cal day" id="d_' + id + '">' + i + '</td>';
        }
      } else {
        html += '<td class="not-current td_month_cal day" id="d_' + id + '">' + i + '</td>';
      }
      // закрыть строку в воскресенье
      if (dow == 0) {
        html += '</tr>';
      }
      // Если последний день месяца не воскресенье, показать первые дни следующего месяца
      else if (i == lastDateOfMonth) {
        var k = 1;
        for (dow; dow < 7; dow++) {
          html += '<td class="not-current td_month_cal">' + k + '</td>';
          k++;
        }
      }
      i++;
    } while (i <= lastDateOfMonth);
    // Конец таблицы
    html += '</table>';
    // Записываем HTML в div
    document.getElementById(this.divId).innerHTML = html;
  };

  Month.prototype.listener = function () {
    // function for html of times output
    function times_show(date_id) {
      let sl = date_id.slice(2);
      let dt = new Date(new Date().setTime(sl))
      let html = ''
      let date_time_array = sort_date_time_arr()
      let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

      if (dt in date_time_array) {
        html += '<div class="master_times" style="" id="td_' + new Date(dt).getTime() + '"> <p>' + capitalizeFirstLetter(dt.toLocaleDateString(locale, options)) + '</p>';
        const times = date_time_array[dt];
        let markm = '';
        for (const time in times) {
          if (new Date(time).getTime() < (new Date().addHours(time_not_allowed))) {
            markm = 'disabled';
          } else {
            markm = times[time];
          }

          html += '<div class="master_time ">\
                            <input type="radio" id="t_' + new Date(time).getTime() + '" name="time" value="' + new Date(time).getTime() + '" ' + markm + ' required />\
                            <label for="t_' + new Date(time).getTime() + '">' + pad(new Date(time).getHours()) + ':' + pad(new Date(time).getMinutes()) + '</label>\
                          </div>';
        }
        html += '</div> '
      }
      return html
    }

    let tc = document.querySelector(".today")
    let id = (tc) ? tc.id : false
    let div_for_time = document.querySelector('#' + this.div_id_for_times_out)
    if (id) div_for_time.innerHTML = times_show(id);

    var el = document.querySelectorAll(".normal.day, .today.day");
    for (var i = 0; i < el.length; i++) {
      el[i].onclick = function (e) {
        document.querySelector(".today").classList.remove("today")
        e.target.classList.add("today")
        let id = e.target.id;
        div_for_time.innerHTML = times_show(id);
      };
    }
    // let's check that there is enough time
    serv_dur_month()
  }

  function month_calendar() {
    // OUTPUT MONTH CALENDAR
    let pre_html = '<div class="calendar-wrapper">\
            <button id="btnPrev" type="button"> < Предыдущий</button>\
            <button id="btnNext" type="button">Следующий > </button>\
            <div id="divCal"></div>\
            <div id="divTimes"></div>\
            </div>';
    //div_month.classList.add('calendar-wrapper')
    div_month.innerHTML = '<h3 class="back shad rad pad margin_rlb1">Выберите дату и время</h3>' + pre_html
    div_month.classList.add('calendar-wrapper')
    div_month.innerHTML = pre_html
    var c = new Month("divCal", "divTimes");
    c.showcurr();
    c.listener();

    getId('btnNext').onclick = function () {
      c.nextMonth();
    };
    getId('btnPrev').onclick = function () {
      c.previousMonth();
    };
    function getId(id) {
      return document.getElementById(id);
    }
  }

  // short calendar
  var Short = function () {
    var marked_date_obj = marked_dates();
    let dates_arr = Object.keys(marked_date_obj);
    let date_time_array = sort_date_time_arr();

    //console.log(dates_arr);
    //let options = { weekday: 'short', month: 'short', day: 'numeric' };
    let options = { month: 'short' };
    let view = '<div class="master_datetime">'
    var formatted = dates_arr.map(item =>
      '<div class="master_date">\
            <input type="radio" class="dat" id="d_'+ new Date(item).getTime() + '" name="date" value="' + new Date(item).getTime() + '" ' + marked_date_obj[item] + ' required />\
            <label for="d_'+ new Date(item).getTime() + '" class="dat_label">'
      + capitalizeFirstLetter(getDayName(item, locale, long)) + '<br />' + pad(new Date(item).getDate()) + ' ' + months[new Date(item).getMonth()] +
      '</label>\
            </div>'
    );

    //document.querySelector('#content').innerHTML = formatted.join('');
    view += formatted.join('');
    for (const date in date_time_array) {
      if (Object.hasOwnProperty.call(date_time_array, date)) {
        view += '<div class="master_times" style="display:none;" id="td_' + new Date(date).getTime() + '"> ';

        const times = date_time_array[date];
        let markk = '';
        for (const time in times) {
          if (new Date(time).getTime() < new Date().addHours(time_not_allowed)) {
            markk = 'disabled';
          } else {
            markk = times[time];
          }
          view += '<div class="master_time ">\
                          <input type="radio" id="t_' + new Date(time).getTime() + '" name="time" value="' + new Date(time).getTime() + '" ' + markk + ' required />\
                          <label for="t_' + new Date(time).getTime() + '">' + pad(new Date(time).getHours()) + ':' + pad(new Date(time).getMinutes()) + '</label>\
                        </div>';
        }
        view += '</div> '
      }
    }
    view += '</div> '
    //document.querySelector('#content').innerHTML = formatted.join('') + view;
    div_short.innerHTML = view;
  }

  Short.prototype.listener = function () {
    let tc = document.querySelector(".dat:checked")
    let id = (tc) ? tc.id : false
    let div_for_time = document.querySelector('#t' + id)
    if (!!div_for_time) { div_for_time.style.display = "block" }

    let ele = document.querySelectorAll(".dat");
    for (var i = 0; i < ele.length; i++) {
      ele[i].onclick = function (e) {
        document.querySelectorAll('.master_times').forEach(function (ee) {
          if (ee.id == 't' + e.target.id) {
            ee.style.display = '';
          }
          else {
            ee.style.display = 'none';
          }
        });
        let times = document.querySelectorAll('.master_datetime input[name="time"]');
        times.forEach(function (eee) {
          if (eee.checked) {
            eee.checked = false;
          }
        });
      };
    }
    // let's check that there is enough time
    serv_duration()
  }

  function short_calendar() {
    var html_short = new Short();
    html_short.listener();
  }


  //////////////////////////////////////////////////////////
  var Schedule = function (year, month) {
    this.y = (!!year) ? year : new Date().getFullYear()
    this.m = (!!month) ? month : new Date().getMonth()

    this.dates = function (y, m) {
      md = []
      let monthDays = new Date(y, m + 1, 0).getDate();
      for (let i = 1; i <= monthDays; i++) {
        let date = new Date(y, m, i, 0, 0, 0, 0)
        md.push(date)
      }
      return md
    }

    this.next_month = function () {
      if (this.m == 11) {
        this.m = 0;
        this.y = this.y + 1;
      }
      else {
        this.m = this.m + 1;
      }
      return this.current_month()
    };

    this.prev_month = function () {
      if (this.m == 0) {
        this.m = 11;
        this.y = this.y - 1;
      }
      else {
        this.m = this.m - 1;
      }
      return this.current_month()
    };

    this.current_month = function () {
      return this.schedule_get(this.dates(this.y, this.m))
    };

    // таблица для графика работы
    this.schedule_get = function (da) {
      let marked_date_obj = marked_dates()
      let m = { month: 'long' };
      let y = { year: 'numeric' };
      let holiday = holidays()
      let restdays = rest_days()
      let dates_arr = Object.keys(marked_date_obj);
      //let date_time_array = sort_date_time_arr()
      let date_time_array = rest_dt()

      let timess = times(Date.now())
      let ti = []
      for (const time in timess) {
        if (Object.hasOwnProperty.call(timess, time)) {
          const hour = new Date(time).getHours();
          const min = new Date(time).getMinutes();
          const t = pad(hour) + ':' + pad(min)
          ti.push(t)
        }
      }
      ti.sort()
      //console.log(ti);

      let html = '<div class="clear shed_wrap">\
                          <table class="tc_table">\
                            <thead class=""><tr class="headdate"><th class="tc_td headcol head">&nbsp;</th>'

      let weekend_days = []
      let weekend_times = {}
      for (const [key, value] of Object.entries(data_obj.org_weekend)) {
        if (value === '') {
          weekend_days.push(`${key}`)
        } else {
          //weekendtimes
          weekend_times[key] = value;
        }
      }
      //dates out
      for (let index = 0; index < da.length; index++) {
        const date = da[index];
        let dis = (date in marked_date_obj) ? 'tc_' + marked_date_obj[date] : '';
        let mark = (dis === "tc_disabled") ? 'tc_checked' : '';
        if (mark === '' && (weekend_days.includes(dw[date.getDay()]) || holiday.includes(date.getTime()) || restdays.includes(date.getTime()))) {
          mark = 'tc_checked'
        }
        html += '<th class="tc_td ' + mark + ' head" id="d_' + new Date(date).getTime() + '">'
          + capitalizeFirstLetter(getDayName(date, locale, long)) + '<br />' +
          pad(new Date(date).getDate()) + '.' + pad(new Date(date).getMonth() + 1) +
          //(new Date(date).toLocaleDateString(locale, m) + 'a').replace(/[ьй]а$/, 'я')  + '<br />\
          '</th>'
      }
      html += '</tr></thead><tbody class="master_times">'
      //console.log(date_time_array)
      //times out
      for (let i = 0; i < ti.length; i++) {
        const time = ti[i];
        const hm = time.split(':')
        html += '<tr class=""><td class="tc_td headcol">' + time + '</td>'
        // цикл по объекту времен, если такой даты нет в массиве времен - для нее вывод времен отдельно
        for (let index = 0; index < da.length; index++) {
          const t = new Date(new Date(da[index]).setHours(hm[0], hm[1], 0, 0))
          let mark = ''
          // check weekend, holiday, full restday
          if (weekend_days.includes(dw[da[index].getDay()]) || holiday.includes(da[index].getTime()) || restdays.includes(da[index].getTime())) {
            mark = 'tc_checked'
          }
          if (mark === '' && da[index] in date_time_array) {
            //check if resttimes
            const times = date_time_array[da[index]]

            if (t in times && times[t] === 'disabled') {
              mark = 'tc_checked'
            }
          }
          //check lunch
          let lunchh = lunch(da[index])
          lunchstart_dt = lunchh[0]
          lunchend_dt = lunchh[1]
          if (t >= lunchstart_dt && t < lunchend_dt) {
            mark = 'tc_checked'
          }
          //check if weekend time
          if (weekend_times.hasOwnProperty(dw[da[index].getDay()])  ) {
            let wt = weekend_times[dw[da[index].getDay()]].split(':');
            let weekend_time = new Date(new Date(da[index]).setHours(wt[0], wt[1], 0, 0))
            if (t.getTime() >= weekend_time.getTime()) {
              mark = 'tc_checked'
            }
          }
          html += '<td class="' + mark + ' tc_td" id ="d_' + da[index].getTime() + 'dt_' + t.getTime() + '">&nbsp;</td>'
        }
        html += '</tr>'
      }
      html += '   </tbody>\
                      </table>\
                    </div>';
      //div_sched.style.cssText = 'overflow-y: auto;'
      return html
    }

    this.listener = function () {
      //schedule output
      let my = { month: 'long', year: 'numeric' };
      let pre_html = '<div class="text_center hor_center schedule_caption">\
                              <span id="btnPre"> < </span>\
                              <span class="month">'+ capitalizeFirstLetter(new Date(this.y, this.m, 1, 0, 0, 0, 0).toLocaleDateString(locale, my)).slice(0, -2) + '</span>\
                              <span id="btnNex"> > </span>\
                            </div>\
                            <div id="shed"></div>';
      div_sched.innerHTML = pre_html
      document.querySelector('#shed').innerHTML = this.current_month()
      that = this
      sel_nex = document.getElementById('btnNex')
      sel_pre = document.getElementById('btnPre')
      sel_pre.onclick = function () {
        document.querySelector('#shed').innerHTML = that.prev_month();
        document.querySelector('.month').innerHTML = capitalizeFirstLetter(new Date(that.y, that.m, 1, 0, 0, 0, 0).toLocaleDateString(locale, my)).slice(0, -2)
        that.listener();
      };
      sel_nex.onclick = function () {
        document.querySelector('#shed').innerHTML = that.next_month();
        document.querySelector('.month').innerHTML = capitalizeFirstLetter(new Date(that.y, that.m, 1, 0, 0, 0, 0).toLocaleDateString(locale, my)).slice(0, -2)
        that.listener();
      };
      let now_date = new Date().setHours(0, 0, 0, 0);
      let dnow = document.getElementById("d_" + now_date);
      if (!!dnow) {
        dnow.scrollIntoView({ behavior: "smooth", block: "nearest", inline: "start" });
      }

      //add or del hidden inputs for shoosed dates
      document.querySelector('.headdate').addEventListener('click', function (el) {
        let dt = el.target.id;
        const times_td = document.querySelectorAll('[id^=' + dt + ']')

        const input_hidden = document.createElement("input");
        input_hidden.type = 'hidden'
        input_hidden.value = dt.slice(2)
        input_hidden.id = 'i' + dt
        let inp = document.querySelector('#' + input_hidden.id);
        if (!!inp) {
          inp.parentNode.removeChild(inp);
          if (el.target.classList.contains('tc_mark_for_del')) {
            el.target.classList.remove('tc_mark_for_del')
            el.target.classList.add('tc_checked')
            times_td.forEach(function (elem) {
              elem.classList.remove('tc_mark_for_del')
              elem.classList.add('tc_checked')
            })
          }
          if (el.target.classList.contains('tc_mark_for_add')) {
            el.target.classList.remove('tc_mark_for_add')
            times_td.forEach(function (elem) {
              elem.classList.remove('tc_mark_for_add')
            })
          }
        } else {
          if (el.target.classList.contains('tc_checked')) {
            input_hidden.name = 'deldate[]'
            el.target.classList.remove('tc_checked')
            el.target.classList.add('tc_mark_for_del')
            times_td.forEach(function (elem) {
              elem.classList.remove('tc_checked')
              elem.classList.add('tc_mark_for_del')
            })
          } else {
            input_hidden.name = 'date[]'
            el.target.classList.add('tc_mark_for_add')
            times_td.forEach(function (elem) {
              elem.classList.add('tc_mark_for_add')
            })
          }
          document.querySelector('.shed_wrap').appendChild(input_hidden)
        }
      })

      // add or del hidden inputs for shoosed times
      let now_time = new Date().getTime()
      document.querySelector('.master_times').addEventListener('click', function (element) {
        let tt = element.target.id
        let dt = tt.split('dt_')
        let time = dt[1]

        const input_hidden = document.createElement("input");
        input_hidden.type = 'hidden'
        input_hidden.name = 'deltime[]'
        input_hidden.id = 'dt' + tt
        input_hidden.value = time
        let inp = document.querySelector('#dt' + tt)

        if (!!inp) {
          inp.parentNode.removeChild(inp);
          if (element.target.classList.contains('tc_mark_for_del')) {
            element.target.classList.remove('tc_mark_for_del')
            element.target.classList.add('tc_checked')
          }
          if (element.target.classList.contains('tc_mark_for_add')) {
            element.target.classList.remove('tc_mark_for_add')
          }
        } else {
          if (element.target.classList.contains('tc_checked')) {
            input_hidden.name = 'deltime[]'
            element.target.classList.remove('tc_checked')
            element.target.classList.add('tc_mark_for_del')
          } else {
            input_hidden.name = 'daytime[]'
            element.target.classList.add('tc_mark_for_add')
          }
          document.querySelector('.shed_wrap').appendChild(input_hidden)
        }
      })
      ////////////////////////////////////
      let reset_button = document.querySelector('#zapis_usluga_form_res');
      let submit_button = document.querySelector('#zapis_usluga_form_sub');
      let formmm = document.getElementById("zapis_usluga_form");
      if (!!formmm) {
        formmm.addEventListener("click", function (element) {
          if (!!reset_button) { reset_button.disabled = false; }
          if (!!submit_button) { submit_button.disabled = false; }

          if (element.target.id == 'zapis_usluga_form_res') {
            document.querySelectorAll('.tc_mark_for_add').forEach((item) => {
              item.classList.remove('tc_mark_for_add')
            });
            document.querySelectorAll('.tc_mark_for_del').forEach((item) => {
              item.classList.remove('tc_mark_for_del')
              item.classList.add('tc_checked')
            });
            document.querySelectorAll('input').forEach((item) => {
              if (!item.classList.contains('buttons')) {
                item.remove();
              }
            });
          }

          if (element.target.id == 'zapis_usluga_form_sub') {
            formmm.submit();
          }
        });
      }
    }
  }
  function schedule_calendar() {
    var html = new Schedule()
    html.listener()
  }

  if (calendar === 'short') {
    short_calendar()
  }
  if (calendar === 'schedule') {
    schedule_calendar()
  }
  if (calendar === 'month') {
    month_calendar()
  }
  /*
  formm.method = method
  formm.action = action
  /////////////////////////////////
  let button_reset = document.querySelector('#' + form_id + '_res')
  let button_submit = document.querySelector('#' + form_id + '_sub')
  formm.addEventListener('click', function() {
    if (document.querySelectorAll('#' + form_id + ' input').length > 0) {
      if (!!button_submit) { button_submit.disabled = false; }
      if (!!button_reset) { button_reset.disabled = false; }
      // button submit
      if (!!button_submit) {
          button_submit.onclick = function () {
          formm.submit()
        }
      }
    }
    else {
      if (!!button_submit) { button_submit.disabled = true; }
    }
  });

  //button reset
  if (!!button_reset) {
    button_reset.addEventListener('click',function(){
      formm.reset();
      array = document.querySelectorAll('#' + form_id + ' input')
      for (let i = 0; i < array.length; i++) {
        const element = array[i];
        if (element.name === 'deldate[]') {
          id = 'd_' + element.value
          document.querySelector('#' + id).classList.remove('tc_mark_for_del')
          document.querySelector('#' + id).classList.add('tc_checked')
          const times_td = document.querySelectorAll('[id^='+id+']')
          times_td.forEach(function (elem) {
            elem.classList.remove('tc_mark_for_del')
            elem.classList.add('tc_checked')
          })
        }
        if (element.name === 'date[]') {
          id = 'd_' + element.value
          document.querySelector('#' + id).classList.remove('tc_mark_for_add')
          const times_td = document.querySelectorAll('[id^='+id+']')
          times_td.forEach(function (elem) {
            elem.classList.remove('tc_mark_for_add')
          })
        }
        if (element.name === 'deltime[]') {
          id = element.id.slice(2)
          document.querySelector('#' + id).classList.remove('tc_mark_for_del')
          document.querySelector('#' + id).classList.add('tc_checked')
        }
        if (element.name === 'daytime[]') {
          id = element.id.slice(2)
          document.querySelector('#' + id).classList.remove('tc_mark_for_add')
        }
        if (calendar === 'schedule') {
          element.remove()
        }
      }
      if (!!button_submit) { button_submit.disabled = true; }
      if (!!button_reset) { button_reset.disabled = true; }
    });
  }
*/
}
