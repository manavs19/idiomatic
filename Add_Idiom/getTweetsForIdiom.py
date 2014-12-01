"""Stores idiom and it's tweets in db"""
import json
import traceback
import MySQLdb

from time import sleep
import times
import requests
from requests_oauthlib import OAuth1
import sys

consumer_key = "8T2NIBinSHcqbBwmeX9v6b9y9"
consumer_secret = "v03WaNkiFRwxCJpkC1yPwa6i8sdXmF1J4AqkFfagyR6cJvCkhf"
access_key = "1008919076-M0Nt8Yi05H5VbVIzdh4F74jsfqkbODzuJsVmccV"
access_secret = "xGDGJ2kyEWWXx2vBomIhcgz02D2ZCgDGmJk0PsWx3W90T"

tweet_count = 100

db = None
twitter = None
token = None

def connect():
    """Connect to MySQLdb"""
    global db
    db = MySQLdb.Connect(host="localhost", user="root", passwd="", db="idiomatic", port=3306, charset="utf8", use_unicode=True)

def disconnect():
    """Disconnect from MySQLdb"""
    global db
    db.close()

def authorize():
    """Authorize twitter requests"""
    global token
    # Twitter auth tokens
    token = {
    "client_key": consumer_key,
    "client_secret" : consumer_secret,
    "resource_owner_key": access_key,
    "resource_owner_secret" : access_secret,
    }
    
def searchIdiom(idiom):
    """Returns tweets of idiom"""
    global token
    endpoint = 'https://api.twitter.com/1.1/search/tweets.json'
    auth = OAuth1(signature_type="auth_header", **token)
    
    params = {}
    params["q"]="%23"+idiom
    params["count"] = tweet_count
    try:
        r = requests.get(endpoint, params=params, auth=auth, timeout=60.0)
        print(r)
        if r.status_code==200:    
            return r.json()["statuses"]
        else:
            if r.status_code==429:
                print('limit exceeded')
                sys.exit(0)
            else:
                print('critical situation')
                sys.exit(0)
    except ValueError as e:
        print('Error : ', e)
    except Exception as e1:
        print('Error : ', e1)

def processIdiom(idiom):
    """Stores idiom and it's tweets in db"""
    global db
    cursor = db.cursor()
    
    statuses = searchIdiom(idiom)
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
            profile_image_url = user["profile_image_url"]
            screen_name = user["screen_name"]
            verified = str(user["verified"])
            
            cursor.execute('INSERT INTO tweets(idiom, id_str, text, retweet_count, user_id_str, created_at, entities, name, profile_image_url, screen_name, verified) \
                            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s);' \
                           ,(idiom, id_str, text, retweet_count, user_id_str, created_at, entities, name, profile_image_url, screen_name, verified))
        except Exception as e:
            print('Error : ', e)

    cursor.execute('INSERT INTO idioms(idiom) VALUES (%s);', (idiom,))
    

if __name__ == "__main__":
    connect()
    authorize()
    idiom=sys.argv[1]
    #idiom="ifwedate"
    processIdiom(idiom)
    disconnect()

