# Camagru 🖼️

**Summary:** The goal of this project is to build a web application.

**Version:** 4

---

## Contents 📚
- [I. Forewords](#chapter-i-forewords-📝)
- [II. Introduction](#chapter-ii-introduction-🌐)
- [III. Objectives](#chapter-iii-objectives-🎯)
- [IV. General Instructions](#chapter-iv-general-instructions-📋)
- [V. Mandatory Part](#chapter-v-mandatory-part-📌)
  - [V.1. Common Features](#v1-common-features-🌍)
  - [V.2. User Features](#v2-user-features-👤)
  - [V.3. Gallery Features](#v3-gallery-features-🖼️)
  - [V.4. Editing Features](#v4-editing-features-✏️)
  - [V.5. Constraints and Mandatory Things](#v5-constraints-and-mandatory-things-🚧)
- [VI. Bonus Part](#chapter-vi-bonus-part-🌟)
- [VII. Submission and Peer-Evaluation](#chapter-vii-submission-and-peer-evaluation-📮)

---

## Chapter I: Forewords 📝

The history of communication is as old as humanity itself, evolving through incredible revolutions. In 1794, Claude Chappe devised a long-distance communication system using air telegraphs during the French Revolution. These "tours" (towers) allowed for messages to be sent over long distances much faster than horse speed.

By 1844, 534 towers spanned over 5000 km in France. However, the system had two significant drawbacks: it couldn't operate at night due to poor visibility, and each tower required two operators every 15 km. Fortunately, we're now in the 21st century.

---

## Chapter II: Introduction 🌐

You're now ready to build your first web applications like a pro. The web is vast and rich, allowing you to quickly release data and content to the world. Say goodbye to old-fashioned to-do lists and eBusiness websites, and get ready for bigger projects. You'll discover:

- Responsive design
- DOM Manipulation
- SQL Debugging
- Cross Site Request Forgery
- Cross Origin Resource Sharing
- ...

---

## Chapter III: Objectives 🎯

This project challenges you to create a small web application for basic photo and video editing using your webcam and some predefined images. These images should have an alpha channel for proper superposition effects. Users can select an image from a list, take a picture with their webcam, and create a composite image. All captured images should be public, likeable, and commentable.

---

## Chapter IV: General Instructions 📋

- This project will be corrected by humans only. Organize and name your files as you see fit, following these rules:
  - No errors, warnings, or log lines in any console, server-side or client-side. Errors related to `getUserMedia()` due to lack of HTTPS are tolerated.
  - You can use any language for the server-side application, but ensure equivalent functions exist in PHP's standard library.
  - Client-side, use HTML, CSS, and JavaScript.
  - Up-to-date containerization is a must.
  - Secure your application, handle at least the mentioned cases, and think about data privacy.
  - Use any webserver (Apache, Nginx, etc.).
  - Your application must be compatible with Firefox (>= 41) and Chrome (>= 46).
  - Store credentials locally in a `.env` file and ignore it in git.

---

## Chapter V: Mandatory Part 📌

### V.1 Common Features 🌍

Develop a web application with a structured layout (header, main section, footer) that displays correctly on mobile devices. Secure all forms and the entire site. Avoid:

- Storing plain or unencrypted passwords in the database.
- Allowing HTML or JavaScript injection in variables.
- Uploading unwanted content to the server.
- SQL query alteration.
- Using external forms for private data.

### V.2 User Features 👤

- Users can sign up with a valid email, username, and a complex password.
- Users must confirm their account via a link sent to their email.
- Users can log in, request password resets, and log out with one click.
- Users can modify their username, email, or password once logged in.

### V.3 Gallery Features 🖼️

- Public gallery displaying all edited images, ordered by creation date. Connected users can like and comment.
- Notify image authors by email when their image receives a new comment (default preference, deactivatable).
- Paginate image list with at least 5 elements per page.

### V.4 Editing Features ✏️

Accessible only to authenticated users. Page layout includes:

- Main section with webcam preview, superposable images list, and capture button.
- Side section with thumbnails of previous pictures.

- Superposable images must be selectable before capturing a picture.
- Final image creation is done server-side.
- Allow image upload for users without a webcam.
- Users can delete their edited images, but not others'.

### V.5 Constraints and Mandatory Things 🚧

- Authorized languages: Server (Any, limited to PHP standard library), Client (HTML, CSS, JavaScript with native browser API).
- Authorized frameworks: Server (Any, up to PHP standard library), Client (CSS frameworks without forbidden JavaScript).
- Include containerization for deployment.

---

## Chapter VI: Bonus Part 🌟

If you complete the mandatory part perfectly, you can add any bonus features. Some ideas:

- "AJAXify" server exchanges.
- Live preview of edited results on webcam preview.
- Infinite pagination for the gallery.
- Share images on social networks.
- Render animated GIFs.

Bonus parts will only be assessed if the mandatory part is perfect (all requirements met and functioning correctly).

---

## Chapter VII: Submission and Peer-Evaluation 📮

Submit your assignment in your Git repository. Only the work inside your repository will be evaluated during the defense. Double-check folder and file names for accuracy.
