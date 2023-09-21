--
-- PostgreSQL database dump
--

-- Dumped from database version 15.2 (Debian 15.2-1.pgdg110+1)
-- Dumped by pg_dump version 15.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

DROP DATABASE postgres;
--
-- Name: postgres; Type: DATABASE; Schema: -; Owner: -
--

CREATE DATABASE postgres WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'en_US.utf8';


\connect postgres

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: DATABASE postgres; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON DATABASE postgres IS 'default administrative connection database';


--
-- Name: db_esami; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA db_esami;


--
-- Name: tipo_laurea; Type: TYPE; Schema: db_esami; Owner: -
--

CREATE TYPE db_esami.tipo_laurea AS ENUM (
    'Triennale',
    'Magistrale',
    'Magistrale a ciclo unico'
);


--
-- Name: add_cdl(character varying, character varying, db_esami.tipo_laurea); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.add_cdl(IN p_id_cdl character varying, IN p_nome character varying, IN p_tipo db_esami.tipo_laurea)
    LANGUAGE plpgsql
    AS $$

declare
BEGIN
    insert into db_esami.cdl(id_cdl, nome,tipo) values (p_id_cdl, p_nome, p_tipo);
END ;
$$;


--
-- Name: add_docente(character varying, character varying, character varying); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.add_docente(IN p_nome character varying, IN p_cognome character varying, IN p_password character varying)
    LANGUAGE plpgsql
    AS $$

declare
    counter       integer := 0;
    mailStart     varchar := replace((LOWER(CONCAT(p_nome, '.', p_cognome))), ' ', '');
    mailNumber    varchar := mailStart;
    finalMail     varchar := '';
    numberOfUsers integer;
    var_id_utente integer;
BEGIN
    numberOfUsers = (select count(u.id_utente)
                     from db_esami.utenti u
                     where u.email like concat(mailStart, '%'));

    while numberOfUsers != 0
        loop
            counter := counter + 1;
            mailNumber := CONCAT(mailStart, counter);
            numberOfUsers = (select count(u.id_utente)
                             from db_esami.utenti u
                             where u.email like concat(mailNumber, '%'));
            raise notice 'mailNumber %', mailNumber;
            raise notice 'NumOfUsers %', numberOfUsers;
        end loop;
    finalMail := concat(mailNumber, '@unimips.it');
    insert into db_esami.utenti(email, password) values (finalMail, p_password);
    var_id_utente = (select id_utente from db_esami.utenti where email = finalMail);
    INSERT INTO db_esami.docenti(nome, cognome, id_utente)
    VALUES (p_nome, p_cognome, var_id_utente);
END ;
$$;


--
-- Name: add_esame(date, integer, integer); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.add_esame(IN p_data date, IN p_id_insegnamento integer, IN p_id_docente integer)
    LANGUAGE plpgsql
    AS $$

declare
BEGIN
    insert into db_esami.esami(data, id_insegnamento, id_docente)
    values (p_data, p_id_insegnamento, p_id_docente);
END ;
$$;


--
-- Name: add_insegnamento(integer, character varying, integer, character varying, integer); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.add_insegnamento(IN p_semestre integer, IN p_nome character varying, IN p_id_docente integer, IN p_id_cdl character varying, IN p_anno integer)
    LANGUAGE plpgsql
    AS $$

declare
BEGIN
    insert into db_esami.insegnamenti(semestre, nome, id_docente, id_cdl, anno) values (p_semestre, p_nome, p_id_docente, p_id_cdl, p_anno);
END ;
$$;


--
-- Name: add_propedeutico(integer, integer); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.add_propedeutico(IN p_id_insegnamento integer, IN p_id_richiesto integer)
    LANGUAGE plpgsql
    AS $$

declare
BEGIN
    insert into db_esami.propedeutici(id_insegnamento, id_richiesto) values (p_id_insegnamento, p_id_richiesto);
END ;
$$;


--
-- Name: add_segretario(character varying, character varying, character varying); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.add_segretario(IN p_nome character varying, IN p_cognome character varying, IN p_password character varying)
    LANGUAGE plpgsql
    AS $$

declare
    counter       integer := 0;
    mailStart     varchar := replace((LOWER(CONCAT(p_nome, '.', p_cognome))), ' ', '');
    mailNumber    varchar := mailStart;
    finalMail     varchar := '';
    numberOfUsers integer;
    var_id_utente integer;
BEGIN
    numberOfUsers = (select count(u.id_utente)
                     from db_esami.utenti u
                     where u.email like concat(mailStart, '%'));

    while numberOfUsers != 0
        loop
            counter := counter + 1;
            mailNumber := CONCAT(mailStart, counter);
            numberOfUsers = (select count(u.id_utente)
                             from db_esami.utenti u
                             where u.email like concat(mailNumber, '%'));
            raise notice 'mailNumber %', mailNumber;
            raise notice 'NumOfUsers %', numberOfUsers;
        end loop;
    finalMail := concat(mailNumber, '@unimips.it');
    insert into db_esami.utenti(email, password) values (finalMail, p_password);
    var_id_utente = (select id_utente from db_esami.utenti where email = finalMail);
    INSERT INTO db_esami.segreteria(nome, cognome, id_utente)
    VALUES (p_nome, p_cognome, var_id_utente);
END ;
$$;


--
-- Name: add_studente(character varying, character varying, character varying, character varying); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.add_studente(IN p_nome character varying, IN p_cognome character varying, IN p_id_cdl character varying, IN p_password character varying)
    LANGUAGE plpgsql
    AS $$

declare
    counter       integer := 0;
    mailStart     varchar := replace((LOWER(CONCAT(p_nome, '.', p_cognome))), ' ', '');
    mailNumber    varchar := mailStart;
    finalMail     varchar := '';
    numberOfUsers integer;
    var_id_utente integer;
BEGIN
    numberOfUsers = (select count(u.id_utente)
                     from db_esami.utenti u
                     where u.email like concat(mailStart, '%'));

    while numberOfUsers != 0
        loop
            counter := counter + 1;
            mailNumber := CONCAT(mailStart, counter);
            numberOfUsers = (select count(u.id_utente)
                             from db_esami.utenti u
                             where u.email like concat(mailNumber, '%'));
            raise notice 'mailNumber %', mailNumber;
            raise notice 'NumOfUsers %', numberOfUsers;
        end loop;
    finalMail := concat(mailNumber, '@unimips.it');
    insert into db_esami.utenti(email, password) values (finalMail, p_password);
    var_id_utente = (select id_utente from db_esami.utenti where email = finalMail);
    INSERT INTO db_esami.studenti(nome, cognome, id_utente, id_cdl)
    VALUES (p_nome, p_cognome, var_id_utente, p_id_cdl);
END ;
$$;


--
-- Name: delete_cdl(character varying); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.delete_cdl(IN p_id_cdl character varying)
    LANGUAGE plpgsql
    AS $$
	BEGIN
		delete from db_esami.cdl c
		where c.id_cdl  = p_id_cdl;
	END;
$$;


--
-- Name: delete_docente(integer); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.delete_docente(IN p_id_docente integer)
    LANGUAGE plpgsql
    AS $$
BEGIN
    delete from db_esami.docenti d
    where d.id_docente  = p_id_docente;
END;
$$;


--
-- Name: delete_esame(integer); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.delete_esame(IN p_id_esame integer)
    LANGUAGE plpgsql
    AS $$
	BEGIN
		delete from db_esami.esami i
		where i.id_esame  = p_id_esame;
	END;
$$;


--
-- Name: delete_insegnamento(integer); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.delete_insegnamento(IN p_id_insegnamento integer)
    LANGUAGE plpgsql
    AS $$
	BEGIN
		delete from db_esami.insegnamenti i
		where i.id_insegnamento  = p_id_insegnamento;
	END;
$$;


--
-- Name: delete_iscrizione_esame(integer, integer); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.delete_iscrizione_esame(IN p_matricola integer, IN p_id_esame integer)
    LANGUAGE plpgsql
    AS $$
BEGIN
    if(select e.data from db_esami.get_all_esami_iscritto(p_matricola) e where e.id_esame = p_id_esame) < current_date THEN
        RAISE EXCEPTION 'impossibile cancellare iscrizione a un esame già passato';
    end if;
    delete from db_esami.iscrizioni_esami e
    where e.matricola  = p_matricola AND e.id_esame = p_id_esame;
END;
$$;


--
-- Name: delete_propedeutico(integer, integer); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.delete_propedeutico(IN p_id_insegnamento integer, IN p_id_richiesto integer)
    LANGUAGE plpgsql
    AS $$
BEGIN
    delete from db_esami.propedeutici p
    where p.id_insegnamento = p_id_insegnamento and p.id_richiesto = p_id_richiesto;
END;
$$;


--
-- Name: delete_segretario(integer); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.delete_segretario(IN p_id_segreteria integer)
    LANGUAGE plpgsql
    AS $$
BEGIN
    delete from db_esami.segreteria s
    where s.id_segreteria  = p_id_segreteria;
END;
$$;


--
-- Name: delete_segretario_before_studente_trigger(); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.delete_segretario_before_studente_trigger() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    delete from db_esami.utenti u
    where u.id_utente = OLD.id_utente;
    RETURN OLD;
END;
$$;


--
-- Name: delete_studente(integer); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.delete_studente(IN p_matricola integer)
    LANGUAGE plpgsql
    AS $$
	BEGIN
		delete from db_esami.studenti s
		where s.matricola  = p_matricola;
	END;
$$;


--
-- Name: delete_utente_before_studente_trigger(); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.delete_utente_before_studente_trigger() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    delete from db_esami.utenti u
    where u.id_utente = OLD.id_utente;
    RETURN OLD;
END;
$$;


--
-- Name: get_all_esami_iscritto(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_all_esami_iscritto(p_matricola integer) RETURNS TABLE(matricola integer, id_esame integer, voto integer, data_verbalizzazione date, data date, id_insegnamento integer, id_docente integer, semestre integer, nome_insegnamento character varying, id_cdl character varying, anno integer, nome_docente character varying, cognome_docente character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select e.matricola,
               e.id_esame,
               e.voto,
               e.data_verbalizzazione,
               e2.data,
               e2.id_insegnamento,
               e2.id_docente,
               e2.semestre,
               e2.nome_insegnamento,
               e2.id_cdl,
               e2.anno,
               e2.nome_docente,
               e2.cognome_docente
        from db_esami.iscrizioni_esami e
                 inner join db_esami.esami_info e2 on e.id_esame = e2.id_esame
        where e.matricola = p_matricola
        order by e2.data desc;
end;

$$;


--
-- Name: get_all_studenti_archiviati(); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_all_studenti_archiviati() RETURNS TABLE(matricola integer, id_cdl character varying, nome character varying, cognome character varying, laureato boolean, nome_cdl character varying, tipo_cdl db_esami.tipo_laurea)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select a.matricola, a.id_cdl, a.nome, a.cognome, a.laureato, c.nome as nome_cdl, tipo as tipo_cdl
        from db_esami.archivio_studenti a
                 inner join db_esami.cdl c on a.id_cdl = c.id_cdl
        order by a.data_archiviazione desc;
end;

$$;


--
-- Name: get_all_verbali_by_matricola_archiviata(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_all_verbali_by_matricola_archiviata(p_matricola integer) RETURNS TABLE(matricola integer, id_esame integer, voto integer, data_verbalizzazione date, data date, id_insegnamento integer, id_docente integer, semestre integer, nome_insegnamento character varying, id_cdl character varying, anno integer, nome_docente character varying, cognome_docente character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select e.matricola_archiviata,
               e.id_esame,
               e.voto,
               e.data_verbalizzazione,
               e2.data,
               e2.id_insegnamento,
               e2.id_docente,
               e2.semestre,
               e2.nome_insegnamento,
               e2.id_cdl,
               e2.anno,
               e2.nome_docente,
               e2.cognome_docente
        from db_esami.archivio_verbali e
                 inner join db_esami.esami_info e2 on e.id_esame = e2.id_esame
        where e.matricola_archiviata = p_matricola
        order by e2.data desc;
end;

$$;


--
-- Name: get_carriera_valida(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_carriera_valida(p_matricola integer) RETURNS TABLE(matricola integer, id_esame integer, voto integer, data_verbalizzazione date, data date, id_insegnamento integer, id_docente integer, semestre integer, nome_insegnamento character varying, id_cdl character varying, anno integer, nome_docente character varying, cognome_docente character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select e.matricola,
               e.id_esame,
               e.voto,
               e.data_verbalizzazione,
               e.data,
               e.id_insegnamento,
               e.id_docente,
               e.semestre,
               e.nome_insegnamento,
               e.id_cdl,
               e.anno,
               e.nome_docente,
               e.cognome_docente
        from db_esami.get_all_esami_iscritto(p_matricola) e
        where e.voto >= 18
          AND not exists(select *
                         from db_esami.get_all_esami_iscritto(p_matricola) e3
                         where e3.id_insegnamento = e.id_insegnamento
                           and e3.data > e.data)
        order by e.data desc;

end;

$$;


--
-- Name: get_carriera_valida_archiviata(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_carriera_valida_archiviata(p_matricola integer) RETURNS TABLE(matricola integer, id_esame integer, voto integer, data_verbalizzazione date, data date, id_insegnamento integer, id_docente integer, semestre integer, nome_insegnamento character varying, id_cdl character varying, anno integer, nome_docente character varying, cognome_docente character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select e.matricola,
               e.id_esame,
               e.voto,
               e.data_verbalizzazione,
               e.data,
               e.id_insegnamento,
               e.id_docente,
               e.semestre,
               e.nome_insegnamento,
               e.id_cdl,
               e.anno,
               e.nome_docente,
               e.cognome_docente
        from db_esami.get_all_verbali_by_matricola_archiviata(p_matricola) e
        where e.voto >= 18
          AND not exists(select *
                         from db_esami.get_all_esami_iscritto(p_matricola) e3
                         where e3.id_insegnamento = e.id_insegnamento
                           and e3.data > e.data)
        order by e.data desc;

end;

$$;


--
-- Name: get_cdl(character varying); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_cdl(p_id_cdl character varying) RETURNS TABLE(id_cdl character varying, nome character varying, tipo character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select c.id_cdl,
               c.nome,
               c.tipo :: varchar
        from db_esami.cdl c
        where c.id_cdl = p_id_cdl;
end;

$$;


--
-- Name: get_docente(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_docente(p_id_docente integer) RETURNS TABLE(id_docente integer, nome character varying, cognome character varying, id_utente integer, email character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select
            d.id_docente,
            d.nome,
            d.cognome,
            u.id_utente,
            u.email
        from
            db_esami.docenti d
                inner join db_esami.utenti u
                           on d.id_utente = u.id_utente
        where d.id_docente = p_id_docente;
end;

$$;


--
-- Name: get_docente_by_id_utente(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_docente_by_id_utente(p_id_utente integer) RETURNS TABLE(id_docente integer, nome character varying, cognome character varying, id_utente integer, email character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select d.id_docente,
               d.nome,
               d.cognome,
               d.id_utente,
               u.email
        from db_esami.docenti d
                 inner join db_esami.utenti u on u.id_utente = d.id_utente
        where u.id_utente = p_id_utente;
end;

$$;


--
-- Name: get_esame(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_esame(p_id_esame integer) RETURNS TABLE(id_esame integer, data date, id_insegnamento integer, id_docente integer, semestre integer, nome_insegnamento character varying, id_cdl character varying, nome_docente character varying, cognome_docente character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select e.id_esame,
               e.data,
               e.id_insegnamento,
               e.id_docente,
               i.semestre,
               i.nome as nome_insegnamento,
               i.id_cdl,
               d.nome as nome_docente,
               d.cognome as cognome_docente
        from db_esami.esami e
                 inner join db_esami.insegnamenti i on i.id_insegnamento = e.id_insegnamento
                 inner join db_esami.docenti d on i.id_docente = d.id_docente
        where e.id_esame = p_id_esame;
end;

$$;


--
-- Name: get_esami_by_cdl(character varying); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_esami_by_cdl(p_id_cdl character varying) RETURNS TABLE(id_esame integer, data date, id_insegnamento integer, id_docente integer, semestre integer, nome_insegnamento character varying, id_cdl character varying, nome_docente character varying, cognome_docente character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select e.id_esame,
               e.data,
               e.id_insegnamento,
               e.id_docente,
               e.semestre,
               e.nome_insegnamento,
               e.id_cdl,
               e.nome_docente,
               e.cognome_docente
        from db_esami.esami_info e
        where e.id_cdl = p_id_cdl
        order by e.data asc;
end;

$$;


--
-- Name: get_esami_by_id_docente(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_esami_by_id_docente(p_id_docente integer) RETURNS TABLE(id_esame integer, data date, id_insegnamento integer, id_docente integer, semestre integer, nome character varying, id_cdl character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select e.id_esame,
               e.data,
               e.id_insegnamento,
               e.id_docente,
               i.semestre,
               i.nome,
               i.id_cdl
        from db_esami.esami e
                 inner join db_esami.insegnamenti i on i.id_insegnamento = e.id_insegnamento
        where e.id_docente = p_id_docente
    order by e.data asc;
end;

$$;


--
-- Name: get_esami_futuri_by_id_docente(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_esami_futuri_by_id_docente(p_id_docente integer) RETURNS TABLE(id_esame integer, data date, id_insegnamento integer, id_docente integer, semestre integer, nome character varying, id_cdl character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select e.id_esame,
               e.data,
               e.id_insegnamento,
               e.id_docente,
               i.semestre,
               i.nome,
               i.id_cdl
        from db_esami.esami e
                 inner join db_esami.insegnamenti i on i.id_insegnamento = e.id_insegnamento
        where e.id_docente = p_id_docente and e.data > current_date
        order by e.data asc;
end;

$$;


--
-- Name: get_esami_not_iscritto(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_esami_not_iscritto(p_matricola integer) RETURNS TABLE(id_esame integer, data date, id_insegnamento integer, id_docente integer, semestre integer, nome_insegnamento character varying, id_cdl character varying, nome_docente character varying, cognome_docente character varying, propedeutici_mancanti bigint)
    LANGUAGE plpgsql
    AS $$
declare
    studente_cdl varchar := '';
begin
    select st.id_cdl
    into studente_cdl
    from db_esami.studenti st
    where st.matricola = p_matricola;

    return query
        select e.id_esame,
               e.data,
               e.id_insegnamento,
               e.id_docente,
               e.semestre,
               e.nome_insegnamento,
               e.id_cdl,
               e.nome_docente,
               e.cognome_docente,
               (select count(*)
                from
                    db_esami.get_propedeutici_mancanti(e.id_insegnamento, p_matricola)) as propedeutici_mancanti
        from db_esami.esami_info e
        where e.data > CURRENT_DATE
          AND e.id_esame in ((select es.id_esame
                              from db_esami.get_esami_by_cdl(studente_cdl) es)
                             except
                             (select ie.id_esame
                              from db_esami.iscrizioni_esami ie
                              where ie.matricola = p_matricola))
        order by e.data asc;
end;

$$;


--
-- Name: get_esami_passati_by_id_docente(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_esami_passati_by_id_docente(p_id_docente integer) RETURNS TABLE(id_esame integer, data date, id_insegnamento integer, id_docente integer, semestre integer, nome character varying, id_cdl character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select e.id_esame,
               e.data,
               e.id_insegnamento,
               e.id_docente,
               i.semestre,
               i.nome,
               i.id_cdl
        from db_esami.esami e
                 inner join db_esami.insegnamenti i on i.id_insegnamento = e.id_insegnamento
        where e.id_docente = p_id_docente and e.data <= current_date
        order by e.data desc;
end;

$$;


--
-- Name: get_insegnamenti_by_cdl(character varying); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_insegnamenti_by_cdl(p_id_cdl character varying) RETURNS TABLE(id_insegnamento integer, nome character varying, semestre integer, id_cdl character varying, id_docente integer, anno integer, docente_nome character varying, docente_cognome character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select i.id_insegnamento,
               i.nome,
               i.semestre,
               i.id_cdl,
               i.id_docente,
               i.anno,
               i.docente_nome,
               i.docente_cognome
        from db_esami.insegnamenti_info i
        where i.id_cdl = p_id_cdl
        order by i.anno, i.semestre;
end;

$$;


--
-- Name: get_insegnamenti_by_id_docente(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_insegnamenti_by_id_docente(p_id_docente integer) RETURNS TABLE(id_insegnamento integer, semestre integer, nome character varying, id_docente integer, id_cdl character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select i.id_insegnamento, i.semestre, i.nome, i.id_docente, i.id_cdl
        from db_esami.insegnamenti i
                 inner join db_esami.docenti d on i.id_docente = d.id_docente
        where d.id_docente = p_id_docente;
end;

$$;


--
-- Name: get_insegnamento(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_insegnamento(p_id_insegnamento integer) RETURNS TABLE(id_insegnamento integer, id_cdl character varying, semestre integer, nome character varying, id_docente integer, docente_nome character varying, docente_cognome character varying, anno integer)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select i.id_insegnamento,
               i.id_cdl,
               i.semestre,
               i.nome,
               i.id_docente,
               i.docente_nome,
               i.docente_cognome,
               i.anno
        from db_esami.insegnamenti_info i
        where i.id_insegnamento = p_id_insegnamento;
end;

$$;


--
-- Name: get_iscrizione_esame(integer, integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_iscrizione_esame(p_matricola integer, p_id_esame integer) RETURNS TABLE(matricola integer, id_esame integer, voto integer, data_verbalizzazione date, data_esame date, id_insegnamento integer, id_docente integer, nome_insegnamento character varying, nome_studente character varying, cognome_studente character varying, id_cdl character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select i.matricola,
               i.id_esame,
               i.voto,
               i.data_verbalizzazione,
               e.data    as data_esame,
               e.id_insegnamento,
               e.id_docente,
               e.nome_insegnamento,
               s.nome    as nome_studente,
               s.cognome as cognome_studente,
               s.id_cdl
        from db_esami.iscrizioni_esami i
                 inner join db_esami.esami_info e on i.id_esame = e.id_esame
                 inner join db_esami.studenti s on i.matricola = s.matricola
        where i.id_esame = p_id_esame
          AND i.matricola = p_matricola;
end;

$$;


--
-- Name: get_iscrizioni_by_id_esame(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_iscrizioni_by_id_esame(p_id_esame integer) RETURNS TABLE(matricola integer, id_esame integer, voto integer, data_verbalizzazione date, nome character varying, cognome character varying, id_utente integer, id_cdl character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select i.matricola,
               i.id_esame,
               i.voto,
               i.data_verbalizzazione,
               s.nome,
               s.cognome,
               s.id_utente,
               s.id_cdl
        from db_esami.iscrizioni_esami i
                 inner join db_esami.studenti s on s.matricola = i.matricola
        where i.id_esame = p_id_esame;
end;

$$;


--
-- Name: get_next_esami_iscritto(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_next_esami_iscritto(p_matricola integer) RETURNS TABLE(matricola integer, id_esame integer, voto integer, data_verbalizzazione date, data date, id_insegnamento integer, id_docente integer, semestre integer, nome_insegnamento character varying, id_cdl character varying, anno integer, nome_docente character varying, cognome_docente character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select e.matricola,
               e.id_esame,
               e.voto,
               e.data_verbalizzazione,
               e.data,
               e.id_insegnamento,
               e.id_docente,
               e.semestre,
               e.nome_insegnamento,
               e.id_cdl,
               e.anno,
               e.nome_docente,
               e.cognome_docente
        from db_esami.get_all_esami_iscritto(p_matricola) e
        where e.voto is null;
end;

$$;


--
-- Name: get_numero_esami_mancanti(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_numero_esami_mancanti(p_matricola integer) RETURNS TABLE(mancanti bigint)
    LANGUAGE plpgsql
    AS $$
declare
    var_id_cdl         varchar;
    var_esami_carriera bigint;
    var_esami_cdl      bigint;
begin
    select s.id_cdl
    into var_id_cdl
    from db_esami.studenti s
    where s.matricola = p_matricola;
    select count(*) into var_esami_carriera from db_esami.get_carriera_valida(p_matricola);
    select count(*) into var_esami_cdl from db_esami.get_insegnamenti_by_cdl(var_id_cdl);
    return query select (var_esami_cdl - var_esami_carriera) ;
end;

$$;


--
-- Name: get_propedeutici_by_id_insegnamento(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_propedeutici_by_id_insegnamento(p_id_insegnamento integer) RETURNS TABLE(id_richiesto integer, nome_insegnamento character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select p.id_richiesto, i.nome as nome_insegnamento
        from db_esami.propedeutici p
                 inner join db_esami.insegnamenti i on p.id_richiesto = i.id_insegnamento
        where p.id_insegnamento = p_id_insegnamento
        order by i.anno, i.semestre asc;
end;

$$;


--
-- Name: get_propedeutici_mancanti(integer, integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_propedeutici_mancanti(p_insegnamento integer, p_matricola integer) RETURNS TABLE(id_richiesto integer)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select p2.id_richiesto
        from ((select p.id_richiesto
               from db_esami.propedeutici p
               where p.id_insegnamento = p_insegnamento) as p2
            left join
            (select c.id_insegnamento
             from db_esami.get_carriera_valida(p_matricola) c) as c2
              on c2.id_insegnamento = p2.id_richiesto);
end;

$$;


--
-- Name: get_propedeutici_possibili_by_id_insegnamento(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_propedeutici_possibili_by_id_insegnamento(p_id_insegnamento integer) RETURNS TABLE(id_insegnamento integer, nome_insegnamento character varying)
    LANGUAGE plpgsql
    AS $$
declare
    p_id_cdl varchar := (select i2.id_cdl
                     from db_esami.get_insegnamento(p_id_insegnamento) i2);
begin
    return query
        select i.id_insegnamento, i.nome as nome_insegnamento
        from db_esami.insegnamenti i
                 left join db_esami.get_propedeutici_by_id_insegnamento(p_id_insegnamento) p
                           on i.id_insegnamento = p.id_richiesto
        where i.id_cdl = p_id_cdl AND p.id_richiesto is null and i.id_insegnamento != p_id_insegnamento
        order by i.anno, i.semestre asc;
end;

$$;


--
-- Name: get_segretario(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_segretario(p_id_segreteria integer) RETURNS TABLE(id_segreteria integer, nome character varying, cognome character varying, id_utente integer, email character varying, password character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select
            s.id_segreteria,
            s.nome,
            s.cognome,
            u.id_utente,
            u.email,
            u.password
        from
            db_esami.segreteria s
                inner join db_esami.utenti u
                           on s.id_utente = u.id_utente
        where s.id_segreteria = p_id_segreteria;
end;

$$;


--
-- Name: get_segretario_by_id_utente(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_segretario_by_id_utente(p_id_utente integer) RETURNS TABLE(id_segreteria integer, nome character varying, cognome character varying, id_utente integer, email character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select s.id_segreteria,
               s.nome,
               s.cognome,
               u.id_utente,
               u.email
        from db_esami.segreteria s
                 inner join db_esami.utenti u
                            on s.id_utente = u.id_utente
        where u.id_utente = p_id_utente;
end;

$$;


--
-- Name: get_studente(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_studente(p_matricola integer) RETURNS TABLE(matricola integer, nome character varying, cognome character varying, id_utente integer, id_cdl character varying, email character varying, password character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select
            s.matricola ,
            s.nome,
            s.cognome,
            s.id_utente ,
            s.id_cdl,
            u.email,
            u.password
        from
            db_esami.studenti s
                inner join db_esami.utenti u
                           on s.id_utente = u.id_utente
        where s.matricola = p_matricola;
end;

$$;


--
-- Name: get_studente_by_id_utente(integer); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_studente_by_id_utente(p_id_utente integer) RETURNS TABLE(matricola integer, nome character varying, cognome character varying, id_utente integer, id_cdl character varying, email character varying)
    LANGUAGE plpgsql
    AS $$
begin
    return query
        select s.matricola,
               s.nome,
               s.cognome,
               s.id_utente,
               s.id_cdl,
               u.email
        from db_esami.studenti s
                 inner join db_esami.utenti u
                            on s.id_utente = u.id_utente
        where s.id_utente = p_id_utente;
end;

$$;


--
-- Name: get_tipi_laurea(); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_tipi_laurea() RETURNS TABLE(tipo character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
    return query
        select unnest(enum_range(NULL::db_esami.tipo_laurea)) :: varchar AS tipo ;
END;
$$;


--
-- Name: get_utenti(); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.get_utenti() RETURNS TABLE(id_utente integer, nome character varying, cognome character varying, email character varying, password character varying, ruolo text)
    LANGUAGE plpgsql
    AS $$
begin
	return query 
		select
			ur.id_utente,
			ur.nome ,
			ur.cognome ,
			ur.email,
			ur."password" ,
			ur.ruolo
from
			db_esami.utenti_ruolo ur;
end;

$$;


--
-- Name: getutentebyemail(character varying); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.getutentebyemail(p_email character varying) RETURNS TABLE(id_utente integer, nome character varying, cognome character varying, email character varying, password character varying, ruolo text)
    LANGUAGE plpgsql
    AS $$
begin
	return query 
		select
			u.id_utente,
			u.nome,
			u.cognome,
			u."email",
			u."password",
			u.ruolo
from
			db_esami.utenti_ruolo u
where
	u.email = p_email;
end;

$$;


--
-- Name: iscrivi_studente_a_esame(integer, integer); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.iscrivi_studente_a_esame(IN p_matricola integer, IN p_id_esame integer)
    LANGUAGE plpgsql
    AS $$
begin
    insert into db_esami.iscrizioni_esami(matricola, id_esame)
    values (p_matricola, p_id_esame);
END;
$$;


--
-- Name: tr_archivia_studente(); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.tr_archivia_studente() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    var_laureato            bool;
    var_countCarrieraValida int;
    var_countEsamiCdl       int;
BEGIN
    select COUNT(*)
    from db_esami.get_carriera_valida(OLD.matricola)
    into var_countCarrieraValida;

    select COUNT(*)
    from db_esami.get_insegnamenti_by_cdl(OLD.id_cdl)
    into var_countEsamiCdl;

    if var_countCarrieraValida = var_countEsamiCdl then
        var_laureato = true;
    else
        var_laureato = false;
    end if;

    insert into db_esami.archivio_studenti(matricola, nome, cognome, id_cdl, laureato, data_archiviazione)
    select s.matricola, s.nome, s.cognome, s.id_cdl, var_laureato, current_date
         from db_esami.studenti s
         where s.matricola = OLD.matricola;

    insert into db_esami.archivio_verbali(id_esame, matricola_archiviata, data_verbalizzazione, voto)
    select i.id_esame, i.matricola, i.data_verbalizzazione, i.voto
    from db_esami.iscrizioni_esami i
    where matricola = OLD.matricola
      and i.voto is not null
      and i.data_verbalizzazione is not null;

    delete from db_esami.iscrizioni_esami where matricola = OLD.matricola;

    RETURN OLD;
END;
$$;


--
-- Name: tr_delete_utente(); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.tr_delete_utente() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    delete from db_esami.utenti u
    where u.id_utente = OLD.id_utente;
    RETURN OLD;
END;
$$;


--
-- Name: tr_limite_docente_insegnamenti(); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.tr_limite_docente_insegnamenti() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF (select count(i.id_insegnamento) from db_esami.insegnamenti i where i.id_docente = new.id_docente) = 3 THEN
        RAISE EXCEPTION 'raggiunto il limite di insegnamenti per questo docente';
    END IF;
    RETURN NEW;
END;
$$;


--
-- Name: tr_restrizioni_iscrizione_esami(); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.tr_restrizioni_iscrizione_esami() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF (select i.id_cdl
        from db_esami.esami e
                 inner join db_esami.insegnamenti i on e.id_insegnamento = i.id_insegnamento
        where e.id_esame = NEW.id_esame) != (select s.id_cdl
                                             from db_esami.studenti s
                                             where s.matricola = NEW.matricola) THEN
        RAISE EXCEPTION 'impossibile iscriversi a esami di un altro corso di laurea';
    END IF;
    if (select e.data
        from db_esami.esami e
        where new.id_esame = e.id_esame) <= (current_date) THEN
        RAISE EXCEPTION 'impossibile iscriversi a esami passati';
    END IF;
    if (select count(p.id_richiesto)
        from db_esami.get_propedeutici_mancanti((select e2.id_insegnamento
                                                 from db_esami.insegnamenti i2
                                                          inner join db_esami.esami e2 on i2.id_insegnamento = e2.id_insegnamento
                                                 where e2.id_esame = new.id_esame), new.matricola) p) > 0 then
        RAISE EXCEPTION 'impossibile iscriversi per mancanza di uno o più esami propedeutici';
    end if;
    RETURN NEW;
END;
$$;


--
-- Name: tr_singolo_esame_giorno_cdl(); Type: FUNCTION; Schema: db_esami; Owner: -
--

CREATE FUNCTION db_esami.tr_singolo_esame_giorno_cdl() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF (select count(e.id_esame)
        from db_esami.esami_info e
        group by e.id_cdl, e.anno, e.data
        having e.id_cdl =
               (select distinct (id_cdl)
                from db_esami.esami_info ei
                where ei.id_insegnamento = new.id_insegnamento)
           AND e.data = new.data) >= 1 THEN
        RAISE EXCEPTION 'impossibile registrare più di un esame dello stesso anno e dello stesso cdl in un singolo giorno';
    END IF;
    RETURN NEW;
END;
$$;


--
-- Name: update_cdl(character varying, character varying, db_esami.tipo_laurea); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.update_cdl(IN p_id_cdl character varying, IN p_nome character varying, IN p_tipo db_esami.tipo_laurea)
    LANGUAGE plpgsql
    AS $$
begin
    UPDATE db_esami.cdl
    SET nome    = p_nome,
        tipo = p_tipo
    where id_cdl = p_id_cdl;
END;
$$;


--
-- Name: update_docente(integer, character varying, character varying, character varying, character varying); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.update_docente(IN p_id_docente integer, IN p_nome character varying, IN p_cognome character varying, IN p_email character varying, IN p_password character varying)
    LANGUAGE plpgsql
    AS $$
begin
    UPDATE db_esami.docenti
    SET nome    = p_nome,
        cognome = p_cognome
    where id_docente = p_id_docente;

    UPDATE db_esami.utenti
    SET email      = p_email,
        "password" = p_password
    where id_utente in (select id_utente from db_esami.docenti where id_docente = p_id_docente);

END;
$$;


--
-- Name: update_esame(integer, date); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.update_esame(IN p_id_esame integer, IN p_data date)
    LANGUAGE plpgsql
    AS $$
begin
    if(select e.data from db_esami.esami e where e.id_esame = p_id_esame) <= current_date then
        RAISE EXCEPTION 'impossibile aggiornare un esame passato';
    end if;
    UPDATE db_esami.esami e
    SET data = p_data
    where e.id_esame = p_id_esame;
END;
$$;


--
-- Name: update_insegnamento(integer, integer, character varying, integer, character varying, integer); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.update_insegnamento(IN p_id_insegnamento integer, IN p_semestre integer, IN p_nome character varying, IN p_id_docente integer, IN p_id_cdl character varying, IN p_anno integer)
    LANGUAGE plpgsql
    AS $$
begin
    UPDATE db_esami.insegnamenti
    SET nome       = p_nome,
        semestre   = p_semestre,
        id_docente = p_id_docente,
        id_cdl     = p_id_cdl,
        anno       = p_anno
    where id_insegnamento = p_id_insegnamento;
END;
$$;


--
-- Name: update_segretario(integer, character varying, character varying, character varying, character varying); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.update_segretario(IN p_id_segreteria integer, IN p_nome character varying, IN p_cognome character varying, IN p_email character varying, IN p_password character varying)
    LANGUAGE plpgsql
    AS $$
begin
    UPDATE db_esami.segreteria
    SET nome    = p_nome,
        cognome = p_cognome
    where id_segreteria = p_id_segreteria;

    UPDATE db_esami.utenti
    SET email      = p_email,
        "password" = p_password
    where id_utente in (select id_utente from db_esami.segreteria where id_segreteria = p_id_segreteria);

END;
$$;


--
-- Name: update_studente(integer, character varying, character varying, character varying, character varying); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.update_studente(IN p_matricola integer, IN p_nome character varying, IN p_cognome character varying, IN p_email character varying, IN p_password character varying)
    LANGUAGE plpgsql
    AS $$
begin
    UPDATE db_esami.studenti
    SET nome    = p_nome,
        cognome = p_cognome
    where matricola = p_matricola;

    UPDATE db_esami.utenti
    SET email      = p_email,
        "password" = p_password
    where id_utente in (select id_utente from db_esami.studenti where matricola = p_matricola);

END;
$$;


--
-- Name: update_user_password(integer, character varying); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.update_user_password(IN p_id_utente integer, IN p_password character varying)
    LANGUAGE plpgsql
    AS $$
begin
    UPDATE db_esami.utenti u
    SET password = p_password
    where u.id_utente = p_id_utente;
END;
$$;


--
-- Name: update_valutazione(integer, integer, integer); Type: PROCEDURE; Schema: db_esami; Owner: -
--

CREATE PROCEDURE db_esami.update_valutazione(IN p_id_esame integer, IN p_matricola integer, IN p_voto integer)
    LANGUAGE plpgsql
    AS $$
begin
    UPDATE db_esami.iscrizioni_esami
    SET voto    = p_voto,
        data_verbalizzazione = current_date
    where id_esame = p_id_esame and matricola = p_matricola;
END;
$$;


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: archivio_studenti; Type: TABLE; Schema: db_esami; Owner: -
--

CREATE TABLE db_esami.archivio_studenti (
    matricola integer NOT NULL,
    id_cdl character varying NOT NULL,
    nome character varying NOT NULL,
    cognome character varying NOT NULL,
    laureato boolean NOT NULL,
    data_archiviazione date NOT NULL
);


--
-- Name: archivio_verbali; Type: TABLE; Schema: db_esami; Owner: -
--

CREATE TABLE db_esami.archivio_verbali (
    id_esame integer NOT NULL,
    matricola_archiviata integer NOT NULL,
    data_verbalizzazione date NOT NULL,
    voto integer NOT NULL
);


--
-- Name: cdl; Type: TABLE; Schema: db_esami; Owner: -
--

CREATE TABLE db_esami.cdl (
    nome character varying NOT NULL,
    tipo db_esami.tipo_laurea NOT NULL,
    id_cdl character varying NOT NULL
);


--
-- Name: docenti; Type: TABLE; Schema: db_esami; Owner: -
--

CREATE TABLE db_esami.docenti (
    id_docente integer NOT NULL,
    nome character varying NOT NULL,
    cognome character varying NOT NULL,
    id_utente integer NOT NULL
);


--
-- Name: docenti_id_docente_seq; Type: SEQUENCE; Schema: db_esami; Owner: -
--

ALTER TABLE db_esami.docenti ALTER COLUMN id_docente ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME db_esami.docenti_id_docente_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: utenti; Type: TABLE; Schema: db_esami; Owner: -
--

CREATE TABLE db_esami.utenti (
    id_utente integer NOT NULL,
    email character varying NOT NULL,
    password character varying NOT NULL
);


--
-- Name: docenti_info; Type: VIEW; Schema: db_esami; Owner: -
--

CREATE VIEW db_esami.docenti_info AS
 SELECT u.id_utente,
    d.nome,
    d.cognome,
    d.id_docente,
    u.email
   FROM (db_esami.utenti u
     JOIN db_esami.docenti d ON ((d.id_utente = u.id_utente)))
  ORDER BY u.id_utente DESC;


--
-- Name: esami; Type: TABLE; Schema: db_esami; Owner: -
--

CREATE TABLE db_esami.esami (
    id_esame integer NOT NULL,
    data date NOT NULL,
    id_insegnamento integer NOT NULL,
    id_docente integer NOT NULL
);


--
-- Name: esami_id_esame_seq; Type: SEQUENCE; Schema: db_esami; Owner: -
--

ALTER TABLE db_esami.esami ALTER COLUMN id_esame ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME db_esami.esami_id_esame_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: insegnamenti; Type: TABLE; Schema: db_esami; Owner: -
--

CREATE TABLE db_esami.insegnamenti (
    id_insegnamento integer NOT NULL,
    semestre integer NOT NULL,
    nome character varying NOT NULL,
    id_docente integer NOT NULL,
    id_cdl character varying NOT NULL,
    anno integer NOT NULL,
    CONSTRAINT insegnamenti_check CHECK (((semestre = 1) OR (semestre = 2)))
);


--
-- Name: esami_info; Type: VIEW; Schema: db_esami; Owner: -
--

CREATE VIEW db_esami.esami_info AS
 SELECT i.id_esame,
    i.data,
    i.id_insegnamento,
    i.id_docente,
    ins.semestre,
    ins.nome AS nome_insegnamento,
    ins.id_cdl,
    ins.anno,
    d.nome AS nome_docente,
    d.cognome AS cognome_docente
   FROM ((db_esami.esami i
     JOIN db_esami.insegnamenti ins ON ((i.id_insegnamento = ins.id_insegnamento)))
     JOIN db_esami.docenti d ON ((i.id_docente = d.id_docente)));


--
-- Name: insegnamenti_id_insegnamento_seq; Type: SEQUENCE; Schema: db_esami; Owner: -
--

ALTER TABLE db_esami.insegnamenti ALTER COLUMN id_insegnamento ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME db_esami.insegnamenti_id_insegnamento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: insegnamenti_info; Type: VIEW; Schema: db_esami; Owner: -
--

CREATE VIEW db_esami.insegnamenti_info AS
 SELECT i.id_insegnamento,
    i.nome,
    i.semestre,
    i.id_cdl,
    i.anno,
    d.id_docente,
    d.nome AS docente_nome,
    d.cognome AS docente_cognome
   FROM (db_esami.insegnamenti i
     JOIN db_esami.docenti d ON ((i.id_docente = d.id_docente)));


--
-- Name: iscrizioni_esami; Type: TABLE; Schema: db_esami; Owner: -
--

CREATE TABLE db_esami.iscrizioni_esami (
    matricola integer NOT NULL,
    id_esame integer NOT NULL,
    voto integer,
    data_verbalizzazione date,
    CONSTRAINT iscrizioni_esami_check CHECK (((voto >= 0) AND (voto <= 30)))
);


--
-- Name: propedeutici; Type: TABLE; Schema: db_esami; Owner: -
--

CREATE TABLE db_esami.propedeutici (
    id_insegnamento integer NOT NULL,
    id_richiesto integer NOT NULL
);


--
-- Name: segreteria; Type: TABLE; Schema: db_esami; Owner: -
--

CREATE TABLE db_esami.segreteria (
    id_segreteria integer NOT NULL,
    nome character varying NOT NULL,
    cognome character varying NOT NULL,
    id_utente integer NOT NULL
);


--
-- Name: segretari_info; Type: VIEW; Schema: db_esami; Owner: -
--

CREATE VIEW db_esami.segretari_info AS
 SELECT u.id_utente,
    s.nome,
    s.cognome,
    s.id_segreteria,
    u.email
   FROM (db_esami.utenti u
     JOIN db_esami.segreteria s ON ((s.id_utente = u.id_utente)))
  ORDER BY u.id_utente DESC;


--
-- Name: segreteria_id_segreterial_seq; Type: SEQUENCE; Schema: db_esami; Owner: -
--

ALTER TABLE db_esami.segreteria ALTER COLUMN id_segreteria ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME db_esami.segreteria_id_segreterial_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: studenti; Type: TABLE; Schema: db_esami; Owner: -
--

CREATE TABLE db_esami.studenti (
    matricola integer NOT NULL,
    nome character varying NOT NULL,
    cognome character varying NOT NULL,
    id_utente integer NOT NULL,
    id_cdl character varying NOT NULL
);


--
-- Name: studenti_info; Type: VIEW; Schema: db_esami; Owner: -
--

CREATE VIEW db_esami.studenti_info AS
 SELECT u.id_utente,
    s.nome,
    s.cognome,
    u.email,
    s.matricola,
    s.id_cdl,
    c.nome AS nomecdl,
    c.tipo
   FROM ((db_esami.utenti u
     JOIN db_esami.studenti s ON ((s.id_utente = u.id_utente)))
     JOIN db_esami.cdl c ON (((s.id_cdl)::text = (c.id_cdl)::text)))
  ORDER BY u.id_utente DESC;


--
-- Name: studenti_matricola_seq; Type: SEQUENCE; Schema: db_esami; Owner: -
--

ALTER TABLE db_esami.studenti ALTER COLUMN matricola ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME db_esami.studenti_matricola_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: utenti_id_utente_seq; Type: SEQUENCE; Schema: db_esami; Owner: -
--

ALTER TABLE db_esami.utenti ALTER COLUMN id_utente ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME db_esami.utenti_id_utente_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: utenti_ruolo; Type: VIEW; Schema: db_esami; Owner: -
--

CREATE VIEW db_esami.utenti_ruolo AS
 SELECT u.id_utente,
    s.nome,
    s.cognome,
    u.email,
    u.password,
    'studente'::text AS ruolo
   FROM (db_esami.utenti u
     JOIN db_esami.studenti s ON ((s.id_utente = u.id_utente)))
UNION
 SELECT u2.id_utente,
    s2.nome,
    s2.cognome,
    u2.email,
    u2.password,
    'segreteria'::text AS ruolo
   FROM (db_esami.utenti u2
     JOIN db_esami.segreteria s2 ON ((s2.id_utente = u2.id_utente)))
UNION
 SELECT u3.id_utente,
    d.nome,
    d.cognome,
    u3.email,
    u3.password,
    'docente'::text AS ruolo
   FROM (db_esami.utenti u3
     JOIN db_esami.docenti d ON ((d.id_utente = u3.id_utente)));


--
-- Data for Name: archivio_studenti; Type: TABLE DATA; Schema: db_esami; Owner: -
--

COPY db_esami.archivio_studenti (matricola, id_cdl, nome, cognome, laureato, data_archiviazione) FROM stdin;
39	L-31	Luca	Corradini	f	2023-09-21
\.


--
-- Data for Name: archivio_verbali; Type: TABLE DATA; Schema: db_esami; Owner: -
--

COPY db_esami.archivio_verbali (id_esame, matricola_archiviata, data_verbalizzazione, voto) FROM stdin;
31	39	2023-09-21	30
\.


--
-- Data for Name: cdl; Type: TABLE DATA; Schema: db_esami; Owner: -
--

COPY db_esami.cdl (nome, tipo, id_cdl) FROM stdin;
Fisica	Triennale	L-30
Informatica	Triennale	L-31
\.


--
-- Data for Name: docenti; Type: TABLE DATA; Schema: db_esami; Owner: -
--

COPY db_esami.docenti (id_docente, nome, cognome, id_utente) FROM stdin;
9	Docente	Fisica	60
8	Alberto	Ceselli	58
6	Massimo	Santini	51
1	Alberto Nunzio	Borghese	7
\.


--
-- Data for Name: esami; Type: TABLE DATA; Schema: db_esami; Owner: -
--

COPY db_esami.esami (id_esame, data, id_insegnamento, id_docente) FROM stdin;
30	2023-11-29	8	8
32	2023-11-30	11	1
33	2023-12-11	9	6
29	2023-09-20	8	8
31	2023-09-10	10	1
36	2023-11-08	10	1
35	2023-09-13	10	1
34	2023-09-05	10	1
\.


--
-- Data for Name: insegnamenti; Type: TABLE DATA; Schema: db_esami; Owner: -
--

COPY db_esami.insegnamenti (id_insegnamento, semestre, nome, id_docente, id_cdl, anno) FROM stdin;
8	1	Programmazione I	8	L-31	1
9	1	Programmazione II	6	L-31	2
10	1	Architettura Degli Elaboratori I	1	L-31	1
11	2	Architettura Degli Elaboratori II 	1	L-31	1
12	1	Fisica I	9	L-30	1
\.


--
-- Data for Name: iscrizioni_esami; Type: TABLE DATA; Schema: db_esami; Owner: -
--

COPY db_esami.iscrizioni_esami (matricola, id_esame, voto, data_verbalizzazione) FROM stdin;
36	29	30	2023-09-21
36	34	18	2023-09-21
36	35	22	2023-09-21
\.


--
-- Data for Name: propedeutici; Type: TABLE DATA; Schema: db_esami; Owner: -
--

COPY db_esami.propedeutici (id_insegnamento, id_richiesto) FROM stdin;
9	8
11	10
\.


--
-- Data for Name: segreteria; Type: TABLE DATA; Schema: db_esami; Owner: -
--

COPY db_esami.segreteria (id_segreteria, nome, cognome, id_utente) FROM stdin;
4	Segretario	Segretario	56
\.


--
-- Data for Name: studenti; Type: TABLE DATA; Schema: db_esami; Owner: -
--

COPY db_esami.studenti (matricola, nome, cognome, id_utente, id_cdl) FROM stdin;
37	Mattia	Delledonne	61	L-30
38	Luca	Favini	62	L-31
36	Matteo	Zagheno	59	L-31
\.


--
-- Data for Name: utenti; Type: TABLE DATA; Schema: db_esami; Owner: -
--

COPY db_esami.utenti (id_utente, email, password) FROM stdin;
61	mattia.delledonne@unimips.it	$2y$10$SU7fzQu68d2k7lAJU9MVeOsYL8zgHlvHHP8oDjcc1OPiAW9S4VVni
62	luca.favini@unimips.it	$2y$10$LwJdROPkVsNnDuE/OpwY7OVOIdTEi4f2KVntYFh0cZaBA3KfKwU3C
56	segretario.segretario@unimips.it	$2y$10$MDOPfPBUaklXvb2xm7JAw.oiINnNnLfgmMYUweHHVP9zJrNdPlUMS
59	matteo.zagheno@unimips.it	$2y$10$TKzFaLXgE79aDN3vrOtqDevnjobVHV6TfL/Lhsq4njBgPn2boWJQS
60	docente.fisica@unimips.it	$2y$10$ZjuuZjsfjh6UAW0wqjEZxeon.kPqIrvRETizWKgd5ITKM7ydgV3U2
58	alberto.ceselli@unimips.it	$2y$10$O7N96saVONvJzVHAM4wsIeFuIAjNur482Ab7AtUBWCWQtyBkXenRK
51	massimo.santini@unimips.it	$2y$10$CmzRbHQrOVz5dxns2xb93.CfvgCGBe/ChSH.qDdg93g2GsugKWHZG
7	albertonunzio.borghese@unimips.it	$2y$10$2/Pti1GD/dkQ..RJTx/nqO1uRoTztXolm70iH/wQZDE733/Fqew8a
54	studentenome.studcognome@unimips.it	$2y$10$vNaqwIAC/Thf4Q4crvIP7eaLdTm6kiihRcUv8DpDLmWdmxrHdNevy
\.


--
-- Name: docenti_id_docente_seq; Type: SEQUENCE SET; Schema: db_esami; Owner: -
--

SELECT pg_catalog.setval('db_esami.docenti_id_docente_seq', 9, true);


--
-- Name: esami_id_esame_seq; Type: SEQUENCE SET; Schema: db_esami; Owner: -
--

SELECT pg_catalog.setval('db_esami.esami_id_esame_seq', 36, true);


--
-- Name: insegnamenti_id_insegnamento_seq; Type: SEQUENCE SET; Schema: db_esami; Owner: -
--

SELECT pg_catalog.setval('db_esami.insegnamenti_id_insegnamento_seq', 12, true);


--
-- Name: segreteria_id_segreterial_seq; Type: SEQUENCE SET; Schema: db_esami; Owner: -
--

SELECT pg_catalog.setval('db_esami.segreteria_id_segreterial_seq', 4, true);


--
-- Name: studenti_matricola_seq; Type: SEQUENCE SET; Schema: db_esami; Owner: -
--

SELECT pg_catalog.setval('db_esami.studenti_matricola_seq', 39, true);


--
-- Name: utenti_id_utente_seq; Type: SEQUENCE SET; Schema: db_esami; Owner: -
--

SELECT pg_catalog.setval('db_esami.utenti_id_utente_seq', 63, true);


--
-- Name: archivio_studenti archivio_studenti_pk; Type: CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.archivio_studenti
    ADD CONSTRAINT archivio_studenti_pk PRIMARY KEY (matricola);


--
-- Name: archivio_verbali archivio_verbali_pk; Type: CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.archivio_verbali
    ADD CONSTRAINT archivio_verbali_pk PRIMARY KEY (matricola_archiviata, id_esame);


--
-- Name: cdl cdl_pk; Type: CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.cdl
    ADD CONSTRAINT cdl_pk PRIMARY KEY (id_cdl);


--
-- Name: docenti docenti_pk; Type: CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.docenti
    ADD CONSTRAINT docenti_pk PRIMARY KEY (id_docente);


--
-- Name: esami esami_pk; Type: CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.esami
    ADD CONSTRAINT esami_pk PRIMARY KEY (id_esame);


--
-- Name: insegnamenti insegnamenti_pk; Type: CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.insegnamenti
    ADD CONSTRAINT insegnamenti_pk PRIMARY KEY (id_insegnamento);


--
-- Name: iscrizioni_esami iscrizioni_esami_pk; Type: CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.iscrizioni_esami
    ADD CONSTRAINT iscrizioni_esami_pk PRIMARY KEY (matricola, id_esame);


--
-- Name: propedeutici propedeutici_pk; Type: CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.propedeutici
    ADD CONSTRAINT propedeutici_pk PRIMARY KEY (id_richiesto, id_insegnamento);


--
-- Name: segreteria segreteria_pk; Type: CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.segreteria
    ADD CONSTRAINT segreteria_pk PRIMARY KEY (id_segreteria);


--
-- Name: studenti studenti_pk; Type: CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.studenti
    ADD CONSTRAINT studenti_pk PRIMARY KEY (matricola);


--
-- Name: utenti utenti_pk; Type: CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.utenti
    ADD CONSTRAINT utenti_pk PRIMARY KEY (id_utente);


--
-- Name: utenti utenti_un_email; Type: CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.utenti
    ADD CONSTRAINT utenti_un_email UNIQUE (email);


--
-- Name: studenti archivia_studente; Type: TRIGGER; Schema: db_esami; Owner: -
--

CREATE TRIGGER archivia_studente BEFORE DELETE ON db_esami.studenti FOR EACH ROW EXECUTE FUNCTION db_esami.tr_archivia_studente();


--
-- Name: segreteria delete_segretario; Type: TRIGGER; Schema: db_esami; Owner: -
--

CREATE TRIGGER delete_segretario AFTER DELETE ON db_esami.segreteria FOR EACH ROW EXECUTE FUNCTION db_esami.tr_delete_utente();


--
-- Name: docenti delete_utente; Type: TRIGGER; Schema: db_esami; Owner: -
--

CREATE TRIGGER delete_utente AFTER DELETE ON db_esami.docenti FOR EACH ROW EXECUTE FUNCTION db_esami.tr_delete_utente();


--
-- Name: studenti delete_utente; Type: TRIGGER; Schema: db_esami; Owner: -
--

CREATE TRIGGER delete_utente AFTER DELETE ON db_esami.studenti FOR EACH ROW EXECUTE FUNCTION db_esami.tr_delete_utente();


--
-- Name: insegnamenti limite_docente_insegnamenti; Type: TRIGGER; Schema: db_esami; Owner: -
--

CREATE TRIGGER limite_docente_insegnamenti BEFORE INSERT ON db_esami.insegnamenti FOR EACH ROW EXECUTE FUNCTION db_esami.tr_limite_docente_insegnamenti();


--
-- Name: esami singolo_esame_giorno_cdl; Type: TRIGGER; Schema: db_esami; Owner: -
--

CREATE TRIGGER singolo_esame_giorno_cdl BEFORE INSERT ON db_esami.esami FOR EACH ROW EXECUTE FUNCTION db_esami.tr_singolo_esame_giorno_cdl();


--
-- Name: iscrizioni_esami tr_restrizioni_iscrizione_esami; Type: TRIGGER; Schema: db_esami; Owner: -
--

CREATE TRIGGER tr_restrizioni_iscrizione_esami BEFORE INSERT ON db_esami.iscrizioni_esami FOR EACH ROW EXECUTE FUNCTION db_esami.tr_restrizioni_iscrizione_esami();


--
-- Name: archivio_studenti archivio_studenti_cdl_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.archivio_studenti
    ADD CONSTRAINT archivio_studenti_cdl_fk FOREIGN KEY (id_cdl) REFERENCES db_esami.cdl(id_cdl);


--
-- Name: archivio_verbali archivio_verbali_archivio_studenti_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.archivio_verbali
    ADD CONSTRAINT archivio_verbali_archivio_studenti_fk FOREIGN KEY (matricola_archiviata) REFERENCES db_esami.archivio_studenti(matricola);


--
-- Name: archivio_verbali archivio_verbali_esami_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.archivio_verbali
    ADD CONSTRAINT archivio_verbali_esami_fk FOREIGN KEY (id_esame) REFERENCES db_esami.esami(id_esame);


--
-- Name: docenti docenti_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.docenti
    ADD CONSTRAINT docenti_fk FOREIGN KEY (id_utente) REFERENCES db_esami.utenti(id_utente) ON DELETE CASCADE;


--
-- Name: esami esami_docenti_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.esami
    ADD CONSTRAINT esami_docenti_fk FOREIGN KEY (id_docente) REFERENCES db_esami.docenti(id_docente);


--
-- Name: esami esami_insegnamenti_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.esami
    ADD CONSTRAINT esami_insegnamenti_fk FOREIGN KEY (id_insegnamento) REFERENCES db_esami.insegnamenti(id_insegnamento);


--
-- Name: insegnamenti insegnamenti_cdl_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.insegnamenti
    ADD CONSTRAINT insegnamenti_cdl_fk FOREIGN KEY (id_cdl) REFERENCES db_esami.cdl(id_cdl);


--
-- Name: insegnamenti insegnamenti_docenti_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.insegnamenti
    ADD CONSTRAINT insegnamenti_docenti_fk FOREIGN KEY (id_docente) REFERENCES db_esami.docenti(id_docente);


--
-- Name: iscrizioni_esami iscrizioni_esami_esami_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.iscrizioni_esami
    ADD CONSTRAINT iscrizioni_esami_esami_fk FOREIGN KEY (id_esame) REFERENCES db_esami.esami(id_esame);


--
-- Name: iscrizioni_esami iscrizioni_esami_studenti_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.iscrizioni_esami
    ADD CONSTRAINT iscrizioni_esami_studenti_fk FOREIGN KEY (matricola) REFERENCES db_esami.studenti(matricola);


--
-- Name: propedeutici propedeutici__insegnamenti_richiesto_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.propedeutici
    ADD CONSTRAINT propedeutici__insegnamenti_richiesto_fk FOREIGN KEY (id_richiesto) REFERENCES db_esami.insegnamenti(id_insegnamento) ON DELETE CASCADE;


--
-- Name: propedeutici propedeutici_insegnamenti_esame_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.propedeutici
    ADD CONSTRAINT propedeutici_insegnamenti_esame_fk FOREIGN KEY (id_insegnamento) REFERENCES db_esami.insegnamenti(id_insegnamento) ON DELETE CASCADE;


--
-- Name: segreteria segreteria_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.segreteria
    ADD CONSTRAINT segreteria_fk FOREIGN KEY (id_utente) REFERENCES db_esami.utenti(id_utente) ON DELETE CASCADE;


--
-- Name: studenti studenti_cdl_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.studenti
    ADD CONSTRAINT studenti_cdl_fk FOREIGN KEY (id_cdl) REFERENCES db_esami.cdl(id_cdl);


--
-- Name: studenti studenti_utenti_fk; Type: FK CONSTRAINT; Schema: db_esami; Owner: -
--

ALTER TABLE ONLY db_esami.studenti
    ADD CONSTRAINT studenti_utenti_fk FOREIGN KEY (id_utente) REFERENCES db_esami.utenti(id_utente);


--
-- PostgreSQL database dump complete
--

