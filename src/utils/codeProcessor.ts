/**
 * Utility function that processes raw HTML strings and removes structural site elements
 * (<header>, <footer>, <nav>, <aside>, <script>) to isolate core functional tool UI.
 *
 * @param rawHtml The input HTML string to sanitize.
 * @returns Cleaned HTML string containing only core tool elements.
 */
export function cleanToolHtmlCode(rawHtml: string): string {
  if (!rawHtml || typeof rawHtml !== 'string') {
    return '';
  }

  // Handle browser DOMParser environment
  if (typeof DOMParser !== 'undefined') {
    const parser = new DOMParser();
    const doc = parser.parseFromString(rawHtml, 'text/html');

    const tagsToRemove = ['header', 'footer', 'nav', 'aside', 'script'];
    tagsToRemove.forEach((tagName) => {
      const elements = doc.body.querySelectorAll(tagName);
      elements.forEach((el) => el.remove());
    });

    return doc.body.innerHTML;
  }

  // Regex fallback for non-DOM environments
  return rawHtml
    .replace(/<header[\s\S]*?<\/header>/gi, '')
    .replace(/<footer[\s\S]*?<\/footer>/gi, '')
    .replace(/<nav[\s\S]*?<\/nav>/gi, '')
    .replace(/<aside[\s\S]*?<\/aside>/gi, '')
    .replace(/<script[\s\S]*?<\/script>/gi, '');
}
