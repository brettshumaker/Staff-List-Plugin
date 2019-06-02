/**
 * Block dependencies
 */
import './style.scss';
import './editor.scss';

/**
 * Custom components
 */
import PostSelector from '../components/PostSelector';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const {
  InspectorControls
} = wp.editor;

/**
 * Register block
 */
export default registerBlockType(
    'simple-staff-list/single-staff-member',
    {
        title: __( 'Single Staff Member', 'simple-staff-list' ),
        description: __( 'Show a single staff member block.', 'simple-staff-list' ),
        category: 'common',
        icon: 'groups',
        attributes: {
            staffMember: {
                type: 'array',
                default: []
            },
            id: {
              type: 'string',
              default: ''
            }
        },
        keywords: [
            __( 'Staff', 'simple-staff-list' ),
            __( 'CTA', 'simple-staff-list' ),
            __( 'Shout Out', 'simple-staff-list' ),
        ],
        edit: props => {
          const { className, attributes, setAttributes } = props;
          console.log('edit',attributes.staffMember);
          return (
            <div className={ className }>
              <InspectorControls>
                <PostSelector
                  onPostSelect={post => {
                    attributes.staffMember.push(post);
                    console.log('onPostSelect post', post)
                    setAttributes({
                      staffMember: [...attributes.staffMember],
                      id: post.id
                    });
                  }}
                  inputPlaceholder='Type to search Staff Members'
                  hideInputOnLimit={true}
                  posts={attributes.staffMember ? attributes.staffMember : [] }
                  onChange={newValue => {
                    setAttributes({
                      staffMember: [...newValue],
                      id: newValue.id
                    });
                  }}
                  postType={'staff-member'}
                  limit="1"
                />
              </InspectorControls>
              <div>
                {attributes.staffMember.map(staffMember => (
                  <p>
                    {staffMember.title}
                  </p>
                ))}
              <p>{attributes.staffMember.length === 0 ? 'Have no staff members :(' : ''}</p>
              </div>
            </div>
          );
        },
        save: () => {
          return null;
        },
    },
);
