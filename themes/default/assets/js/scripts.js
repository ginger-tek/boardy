if (location.hash) document.querySelector(location.hash + ' .body')?.classList.add('focus')

function deletePost(id) {
  if (!confirm('Are you sure you want to delete this post?')) return false
  document.querySelector('#delete-post-' + id).submit()
}

function deleteComment(id) {
  if (!confirm('Are you sure you want to delete this comment?')) return false
  document.querySelector('#delete-comment-' + id).submit()
}

document.querySelectorAll('.d').forEach(e => {
  let d = new Date(e.innerText + 'Z')
  e.innerText = e.classList.contains('t') ? d.toLocaleString() : d.toLocaleDateString()
})