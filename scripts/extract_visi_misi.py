import json
from docx import Document

# Path to the .docx file
DOCX_PATH = r"public/assets/visi & misi perumahan.docx"
JSON_PATH = r"public/assets/visi_misi.json"

def extract_visi_misi(docx_path):
    doc = Document(docx_path)
    content = []
    for para in doc.paragraphs:
        text = para.text.strip()
        if text:
            content.append(text)
    return content

def main():
    content = extract_visi_misi(DOCX_PATH)
    data = {"visi_misi": content}
    with open(JSON_PATH, "w", encoding="utf-8") as f:
        json.dump(data, f, ensure_ascii=False, indent=2)
    print(f"Extracted and saved to {JSON_PATH}")

if __name__ == "__main__":
    main()
