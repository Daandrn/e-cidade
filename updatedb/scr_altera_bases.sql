begin;
select fc_startsession();

update bases set r08_mesusu = 2, r08_anousu = 2016 where r08_codigo ilike '%S%';

commit;