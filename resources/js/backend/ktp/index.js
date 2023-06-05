'use strict'
require('dotenv').config()
const express = require('express')
const expressFileUpload = require('express-fileupload')
const bb = require('express-busboy')
const morgan = require('morgan')
const bodyParser = require('body-parser')
const cors = require('cors')
const path = require('path')
const fs = require('fs')
const jpeg = require('jpeg-js')
const tesseract = require('node-tesseract-ocr')
const sharp = require('sharp')
const cv = require('opencv.js')
const exec = require('child_process').exec
const c = require("cli-color")
const gm = require('gm')
const { nikParser } = require('nik-parser')

const green = c.green
const red = c.red
const yellow = c.yellow

const onError = (error) => {
	console.log(error)
	return 1
}

async function run() {
	const port = 5000
	const app = express()
	app.enable('trust proxy')
	app.disable('x-powered-by')
	app.use(expressFileUpload({ useTempFiles : true }))
	app.use(bodyParser.urlencoded({ extended: false }))
	app.use(bodyParser.json())
	app.use(morgan('common'))
	app.use(cors())

	app.post('/api/ktp/verify-ktp', async (req, res) => {
		try {
			if (req.files && req.files.images) {
				const tempFile = req.files.images.tempFilePath
				const tempFilename = `./tmp/${path.basename(tempFile)}.jpg`
				const convertToBW = await sharp(tempFile).resize(300).toColourspace('b-w').sharpen().toFile(tempFilename)
				if (convertToBW) {
					tesseract.recognize(tempFilename, { lang: 'ind' })
						.then((result) => {
							Promise.all([ fs.unlinkSync(tempFile), fs.unlinkSync(tempFilename) ])
							var text = result.replace('\n', '').trim()
							const nikRegex = /\d{16}/
							const nikMatch = text.match(nikRegex)
							const nik = nikMatch ? nikMatch[0] : ''
							const parse = nikParser(nik)
							const isvalid = parse.isValid()
							const provinsi = parse.province()
							const kabupaten = parse.kabupatenKota()
							const kelamin = parse.kelamin()
							if(nik != '' && isvalid) {
								const normalizeData = { isvalid, nik, provinsi, kabupaten, kelamin }
								return res.status(200).json({ success: true, message: normalizeData })
							}
							else {
								return res.status(500).send('Error while processing image. Please use other image')
							}
						})
						.catch((err) => {
							return res.status(500).send('Error while processing image. Please use other image')
						})
				} else {
					return res.status(500).send('Error while processing image. Please use other image')
				}
			} else {
				return res.status(500).send('Error while processing image. Please use other image')
			}
		} catch (err) {
			return res.status(500).send(err.message)
		}
	})

	app.listen(port, () => console.log(green(`Server started at port ${port}.`)))
}	

run()