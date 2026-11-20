<!doctype html>
<script>
    const res = await fetch("https://libretranslate.com/translate", {
	method: "POST",
	body: JSON.stringify({
		q: "Es el valor de valuación de la propiedad.",
		source: "auto",
		target: "en",
		format: "text",
		alternatives: 3,
		api_key: ""
	}),
	headers: { "Content-Type": "application/json" }
});

console.log(await res.json());
</script>
