"""Stores idiom and it's tweets in db"""
import json
import traceback
import MySQLdb

from time import sleep
import times
import requests
from requests_oauthlib import OAuth1
import sys

consumer_key = ["8T2NIBinSHcqbBwmeX9v6b9y9", "nRWMKFtmRTCSKINTd2l7T6eaW", "YAw05zuEdnBQwnGfLrKrPVDEi", "b9lvZMPNMVJlIF9x8S3fqLC0p"]
consumer_secret = ["v03WaNkiFRwxCJpkC1yPwa6i8sdXmF1J4AqkFfagyR6cJvCkhf", "gzbDe0xUDNaYdcjb3zKc2UGmzFyjq0Op3oFi9OSHe1qhwhsXr0", "TXeyCuP79uYZYB13vJigVLDCaopqjfplWJX4t2n9Ro8r1iXTTJ", "djv6xZf7tubGYWkStu2saAkl76NW6Fahn5ugVdhBGcLChxBz1G"]
access_key = ["1008919076-M0Nt8Yi05H5VbVIzdh4F74jsfqkbODzuJsVmccV", "2800089342-djkbDdw0IJZYA5tRvgSOSdbBhTiazzLuCHHM5O3", "2800428427-6Hhy93W0G2OGCoH9dHaRm8dld5pEgvoKongvhER", "2800402220-SLx6ByT5OH3dx7Nwm6ApX3MEpNJTnGyQmJ9Zq8C"]
access_secret = ["xGDGJ2kyEWWXx2vBomIhcgz02D2ZCgDGmJk0PsWx3W90T", "AHXtKDG74qSRfstvR0FjBsmeInQrj8mYd3E17vnXiwP3y", "DGpwG5XKT7PCisiuKLhJdhmbQgkN6iJslVtibku11aq6I", "rCTTzHHCzzIH0ms4urA2DaN6oqYs1gv6OU72gt0xHtaj2"]

tweet_count = 100

db = None
twitter = None

def connect():
    """Connect to MySQLdb"""
    global db
    db = MySQLdb.Connect(host="127.0.0.1", user="cnerg", passwd="", db="test", charset="utf8", use_unicode=True)

def disconnect():
    """Disconnect from MySQLdb"""
    global db
    db.close()

def authorize(i):
    """Authorize twitter requests"""
    # Twitter auth tokens
    token = {
    "client_key": consumer_key[i],
    "client_secret" : consumer_secret[i],
    "resource_owner_key": access_key[i],
    "resource_owner_secret" : access_secret[i],
    }
    return token


# Constants
RESET_BUFFER  = 5
RATE_LIMIT_BUFFER = 1
RETRY_AFTER   = 5
RETRY_MAX = 2

def rest_rate_limit(r):
    """
    Check the rate limit and sleep it off if hit.
    """

    try:
                #limit  = int(r.headers["X-Rate-Limit-Limit"])
                remain = int(r.headers["X-Rate-Limit-Remaining"])
                reset  = int(r.headers["X-Rate-Limit-Reset"])
                curtime = times.to_unix(times.parse(r.headers["date"]))
    except KeyError as e:
                # We dont have the proper headers
                log.error("Header not found - {}", e)
                sleep(RETRY_AFTER)
                return

    if remain <= RATE_LIMIT_BUFFER:
                log.debug("Hit rate limit - {}", remain)
                log.debug("Rate limit reset in {} seconds", reset - curtime)
                sleep(reset - curtime + RESET_BUFFER)

    
def searchIdiom(i, idiom):
    """Returns tweets of idiom"""
    token = authorize(i)
    endpoint = 'https://api.twitter.com/1.1/search/tweets.json'
    auth = OAuth1(signature_type="auth_header", **token)
    
    params = {}
    params["q"]="%23"+idiom
    params["count"] = tweet_count
    try:
        r = requests.get(endpoint, params=params, auth=auth, timeout=60.0)
        print(r)
        if r.status_code==200:
	    '''
            print("yay")
            for tweet in r.json()["statuses"]:
                print(tweet["user"]["screen_name"])
                #print(tweet["entities"]["hashtags"])
                print tweet["text"]
            '''    
            return r.json()["statuses"]
        else:
            if r.status_code==429:
                print('limit exceeded')
                rest_rate_limit(r)
                return searchIdiom(i, idiom)
            else:
                print('critical situation')
                return []
    except ValueError as e:
        print('Error : ', e)
    except Exception as e1:
        print('Error : ', e1)

def processIdiom(i, idiom):
    """Stores idiom and it's tweets in db"""
    global db
    cursor = db.cursor()
    
    statuses = searchIdiom(i, idiom)
    #Should have at least 10 statuses to be useful
    if len(statuses) < 10:
        return
    # loop through each of my statuses, and print its content
    for status in statuses:
        #print status["text"]
        try:
            id_str = status["id_str"]
            text = status["text"].encode('ascii','ignore')
            retweet_count = status["retweet_count"]
            user = status["user"]
            created_at = status["created_at"]
            entities = status["entities"]
            entities = json.dumps(entities)

            user_id_str = user["id_str"]
            name = user["name"].encode('ascii','ignore')
            screen_name = user["screen_name"]
            description = user["description"].encode('ascii','ignore')
            user_entities = json.dumps(user["entities"])
            followers_count = user["followers_count"]
            listed_count = user["listed_count"]
            profile_image_url = user["profile_image_url"]
            verified = str(user["verified"])

            
            cursor.execute('INSERT IGNORE INTO idiomatic_tweets(idiom, id_str, text, retweet_count, user_id_str, created_at, entities, name, profile_image_url, screen_name, verified) \
                            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s);' \
                           ,(idiom, id_str, text, retweet_count, user_id_str, created_at, entities, name, profile_image_url, screen_name, verified))

            cursor.execute('INSERT IGNORE INTO idiomatic_users(id_str, name, screen_name, description, entities, followers_count, listed_count, profile_image_url, verified) \
                            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s);' \
                           ,(user_id_str, name, screen_name, description, user_entities, followers_count, listed_count, profile_image_url, verified))
        except Exception as e:
            print('Error : ', e)
            print sys.exc_traceback.tb_lineno 

    if statuses:
        cursor.execute('INSERT IGNORE INTO idiomatic_idioms(idiom) VALUES (%s);', (idiom,))
    else:
        print "statuses" , statuses

if __name__ == "__main__":
    connect()
    #authorize()
    fin = open("idioms.txt", "r")
    for i, idiom in enumerate(fin):
        print idiom
        idiom = idiom.rstrip()
        processIdiom(i%4, idiom)
        #raw_input()
    fin.close()
    disconnect()

