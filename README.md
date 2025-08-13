# docx-2-pdf

An HTTP API for converting `.docx` files to `.pdf` using LibreOffice. This project enables automated document conversion via a simple web service.

## Hosted API

Call this API for free at https://docx-2-pdf.protocol7.net - the homepage has instructions.

## How it works

This project builds a PHP+Apache container that, on boot, hosts a simple webpage with two endpoints:

    GET / - Homepage and instructions
    POST / - Conversion endpoint

The conversion endpoint expects a file upload, and will return a PDF file named output.pdf, which you will need to rename on your end.

## License

This project is licensed under the MIT License. See [LICENSE](LICENSE) for details.

## Contributing
Pull requests are welcome! For major changes, please open an issue first to discuss what you would like to change.

## Acknowledgements
- [LibreOffice](https://www.libreoffice.org/) for document conversion
