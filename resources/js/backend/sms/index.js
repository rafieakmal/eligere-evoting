'use strict'
require('dotenv').config()
const bb = require('express-busboy')
const express = require('express')
const bodyParser = require('body-parser')
const cors = require('cors')


const c = require("cli-color")

const green = c.green
const red = c.red
const yellow = c.yellow

function generateNumber() {
	return Math.floor(Math.random() * 900000) + 100000
}

function verifyCode(inputCode) {
	return inputCode != code ? false : true;
}

async function sendMessages(number, message) {
	const accountSid = process.env.TWILIO_ACCOUNT_SID
	const authToken = process.env.TWILIO_AUTH_TOKEN
	const from = process.env.FROM
	const client = require('twilio')(accountSid, authToken)
	try {
		const send = await client.messages.create({
			body: message,
			from: from,
			to: number
		})

		if (send.sid) {
			const data = {
				"success": true,
				"to": number,
			}
			return data
		} else {
			const data = {
				"success": false,
				"to": number,
				"status": "failed"
			}
			return data
		}
	} catch (error){
		onError(error)
	}
}

const onError = (error) => {
	console.log(error)
	return 1
}

async function sendSMS(number, message) {
	try{
		const text = message
		const to = "+62" + number

		console.log(yellow("------------------ Sending Start -------------------"))
			
		const sent = await sendMessages(to, text)
		return sent

	} catch (err) {
		onError(err)
	}
}

async function run() {
	const port = 2020
	const app = express()
	app.use(bodyParser.urlencoded({ extended: false }))
	app.use(bodyParser.json())
	app.use(cors())
	bb.extend(app)
	app.get('/api/number/get-code', async (req, res) => {
		const data = {
			"code": generateNumber()
		}
		res.json(data)
	})

	app.post('/api/number/send-sms', async (req, res) => {
		const to = String(req.body.number) 
		const message = String(req.body.message)
		if(to && message) {
			const data = await sendSMS(to, message)
			res.json(data)
		}
	})

	app.listen(port, () => console.log(green(`Server started at port ${port}.`)))
}

run()