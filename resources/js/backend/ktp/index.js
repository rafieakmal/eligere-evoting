'use strict'
require('dotenv').config()
const express = require('express')
const expressFileUpload = require('express-fileupload')
const morgan = require('morgan')
const bodyParser = require('body-parser')
const cors = require('cors')
const path = require('path')
const fs = require('fs')
const tesseract = require('node-tesseract-ocr')
const sharp = require('sharp')
const { nikParser } = require('nik-parser')


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

	app.post('/api/ktp/verify-nik', async (req, res) => {
		const nik = String(req.body.nik)
		if(req.body.nik) {
			const parse = nikParser(nik)
			const isValid = parse.isValid()
			const province = parse.province()
			const regency = parse.kabupatenKota()
			if(isValid) {
				const data = {
					"valid": isValid,
					"provinsi": province,
					"kabupaten": regency,
				}
				return res.json(data)
			} else {
				const data = {
					"valid": false
				}
				return res.json(data)
			}
		}
		return res.status(500).send('Error while processing NIK')
	})

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
							if(nik != '' && isvalid) {
								const normalizeData = { isvalid, nik }
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

	app.listen(port, () => console.log(`Server started at port ${port}.`))
}	

run()