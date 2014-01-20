#!/bin/sh

convert -resize  90 graz_original.gif graz.gif
convert -resize  80 ibm_original.jpg ibm.jpg
convert -resize 100 medallia_original.gif medallia.gif
convert -resize  50 27m_original.jpg 27m.gif
convert -resize 100 trolltech_original.gif trolltech.gif
convert -resize  70 fast_original.jpg fast.jpg
convert -resize 120 softhouse_original.jpg softhouse.jpg
convert -resize  90 apptus_original.jpg apptus.jpg
convert -resize  70 logipard_original.jpg logipard.jpg

convert -resize 120 fast_original.jpg fast_larger.jpg
convert -resize 200 trolltech_original.gif trolltech_larger.gif
convert -resize 180 medallia_original.gif medallia_larger.gif

convert -resize 200 nc_original.gif nc.gif
