-- Αυτό το αρχείο έχει ως μοναδικό σκοπό να παρουσιάσει τα ερωτήματα SQL
-- που χρησιμοποιήθηκαν στα αρχεία PHP του project. To αρχείο έχει ονομαστεί
-- .sql ώστε να υπάρχει κατάλληλο highlighting σε text-editors για ευκολότερη
-- κατανόηση

-- Στις μεταβλητές της PHP αναθέτονται τιμές μέσω της μεθόοδου POST από
-- FORMS της HTML οι οποίες έχουν υλοποιηθεί στα αρχεία PHP του project.

-- Εδώ, τις μεταβλητές τις παρουσιάζουμε με αυτόν τον τρόπο: <var>

-- _____________________________________________________________________________


-- Ερώτημα 7-i:
-- Βρες όλες τις υπηρεσίες (<type> : που θέλουν/δεν θέλουν εγγραφή)
-- και τα κόστη τους
-- [και] (έχουν ελάχιστο κόστος <min> και μέγιστο κόστος <max>)

select service_description, service_cost
from <type>
where service_description != 'Room'
and service_cost between <min> and <max>;

--όπου type = service_with_subscription ή service_without_subscription"

-- _____________________________________________________________________________


-- Ερώτημα 7-ii:
-- Βρες τις επισκέψεις που έχουν γίνει σε όλους τους χώρους του <place_name>
-- απο την <min_date> μέχρι και την <max_date>

select customer.nfc_id, customer.first_name, customer.last_name,
visited.entry_date_time
from (
  customer join visited on customer.nfc_id = visited.customer_id)
  join place on visited.place_id = place.place_id
  where (
    place_name = <place_name>
    and (timestampdiff(day, entry_date_time, <min_date>) <= 0)
    and (timestampdiff(day, <max_date>, entry_date_time) <= 0))
    order by visited.entry_date_time desc;

-- _____________________________________________________________________________


-- Ερώτημα 9:
-- Βρες τις επισκέψεις που έχουν γίνει σε όλους τους χώρους του ξενοδοχείου
-- απο τον πελάτη με <nfc_id>

select place.place_id, place_name, number, floor_number, corridor,
entry_date_time, exit_date_time
from visited join place on place.place_id = visited.place_id
where customer_id = <nfc_id>
order by entry_date_time;

-- _____________________________________________________________________________


-- Ερώτημα 10:
-- Βρες το NFC-ID, το όνομα και το επίθετο των πελατών που βρέθηκαν στον ίδιο
-- χώρο με τον πελάτη που έχει διαγνωστεί θετικά με κορωνοιό και έχει
-- NFC-ID ίσο με <infected nfc_id> σε χρονικό διάστημα που συμπίπτει με την ώρα άφιξης του
-- μολυσμένου μέχρι και μία ώρα μετά την αναχώρησή του.

-- ipv --> infected_person_visits
-- apv --> all_person_visits

with
ipv (customer_id, place_id, entry_date_time, exit_date_time)
as (select customer_id, place_id, entry_date_time, exit_date_time
    from visited as v
    where v.customer_id = <infected nfc_id>),

apv(customer_id, first_name, last_name, place_id, entry_date_time, exit_date_time)
as (select c.nfc_id, c.first_name, c.last_name, v.place_id, v.entry_date_time, v.exit_date_time
    from customer as c join visited as v on c.nfc_id = v.customer_id)

select distinct apv.customer_id, apv.first_name, apv.last_name
  from ipv join apv on ipv.place_id = apv.place_id
  where
  (
    (apv.entry_date_time between ipv.entry_date_time and date_add(ipv.exit_date_time, interval 1 hour))
    or
    (apv.exit_date_time between ipv.entry_date_time and date_add(ipv.exit_date_time, interval 1 hour))
  ) and apv.customer_id <> <infected nfc-id>;

-- _____________________________________________________________________________


-- Ερώτημα 11a:
-- Βρες το όνομα του μέρους, την αρίθμηση και τις συνολικές επισκέψεις των πρώτων 10
-- πιο πολυσύχναστων χώρων που έχουν γίνει σε αυτό τους τελευταίους <months> μήνες
-- (δηλαδή για τον μήνα 1, ενώ για τον χρόνο 12)
-- από πελάτες με ηλικίες από <min_age>  έως και <max_age> ετών.

select place.place_name, place.number, count(*) as visit_count
from (customer join visited on customer.nfc_id = visited.customer_id)
join place on place.place_id = visited.place_id
where (
  (timestampdiff(year, customer.date_of_birth, curdate())
  between <min_age> and <max_age>)
  and
  (timestampdiff(month, visited.entry_date_time, curdate()) <= <months>)
  )
  group by visited.place_id
  order by visit_count desc, place.place_name
  limit 10;

-- _____________________________________________________________________________


-- Ερώτημα 11b:
-- Βρες το όνομα της υπηρεσίας και τις συνολικές χρήσεις αυτής
-- που έχουν γίνει τους τελευταίους <months> μήνες
-- (δηλαδή για τον μήνα 1, ενώ για τον χρόνο 12)
-- από πελάτες με ηλικίες από <min_age>  έως και <max_age> ετών.

select service_charge.charge_description, count(*) as use_count
from service_charge join customer
on service_charge.customer_id = customer.nfc_id
where
(service_charge.charge_description <> 'Room') and
(timestampdiff(year, customer.date_of_birth, curdate())
between <min_age> and <max_age>) and
(timestampdiff(month, service_charge.charge_date_time, curdate()) <= <months>)
group by service_id
order by use_count desc;

-- _____________________________________________________________________________


-- Ερώτημα 11c:
-- Βρες το όνομα της υπηρεσίας και τους συνολικούς χρήστες αυτής
-- με ηλικίες από <min_age>  έως και <max_age> ετών
-- για τους τελευταίους <months> μήνες
-- (δηλαδή για τον μήνα 1, ενώ για τον χρόνο 12)


with aux as (
  select distinct service_charge.customer_id, service_charge.charge_description
  from customer join service_charge on service_charge.customer_id = customer.nfc_id
  where(
  (service_charge.charge_description <> 'Room') and
  (timestampdiff(year, customer.date_of_birth, curdate())
  between <min_age> and <max_age>) and
  (timestampdiff(month, service_charge.charge_date_time, curdate()) <= <months>))
)
select charge_description, count(*) as use_count
  from aux
  group by aux.charge_description
  order by use_count desc;
